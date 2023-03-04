<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;

class WeatherApiService
{
    private const URL = "https://api.openweathermap.org/data/2.5/weather";
    private Client $client;
    private array $params = [];
    private array $headers = [];

    private array $bulkResponse = [];

    public function __construct()
    {
        $this->params['appid'] = $_ENV['WEATHER_API_KEY'];
        $this->client = new Client([
            'base_url' => self::URL,
        ]);
    }

    /**
     * @return static
     */
    public static function init(): self
    {
        return new static();
    }

    private function getClient(): Client
    {
        return $this->client;
    }

    private function getParams(): array
    {
        return $this->params;
    }

    private function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $lat
     * @param string $lon
     * @return WeatherApiService
     */
    public function setLocation(string $lat, string $lon): self
    {
        $this->params['lat'] = $lat;
        $this->params['lon'] = $lon;
        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function getRequest(string $method, string $url = '', array $options = []): ResponseInterface
    {
        return $this->getClient()->request($method, $url, $options);
    }

    /**
     * @return string|ResponseInterface
     * @throws GuzzleException
     */
    public function fetchCurrent(): string|ResponseInterface
    {
        try {
            return $this->getRequest(method: 'GET', url: self::URL, options: [
                'query' => $this->getParams(),
                'headers' => $this->getHeaders(),
            ]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function pool($requests): PromiseInterface
    {
        $pool = new Pool($this->client, $requests, [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index) {
                dump($response, $index);
            },
            'rejected' => function (RequestException $reason, $index) {
                dump($reason, $index);
            },
        ]);
        return $pool->promise()->wait();
    }

    public function fetchBulk($locations): array
    {
        $requests = function () use ($locations) {
            foreach ($locations as $location) {
                $isCached = Cache::get($location->email);
                if (!$isCached) {
                    $queryParams = $this->getParams();
                    $queryParams['lat'] = $location['latitude'];
                    $queryParams['lon'] = $location['longitude'];
                    $queryParams = http_build_query($queryParams);

                    $url = self::URL . '?' . $queryParams;
                    yield new Request('GET', $url);
                }
            }
        };

        $this->makePool($requests, $locations);

        return $this->bulkResponse;
    }

    public function makePool($requests, $locations): void
    {
        $pool = new Pool($this->getClient(), $requests(), [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index) use ($locations) {
                $this->bulkResponse[$index] = Cache::remember($locations[$index]['email'], '3600',
                    function () use ($response) {
                        return json_decode($response->getBody()->getContents(), true);
                    }
                );
            },
            'rejected' => function (RequestException $reason, $index) {
                $this->bulkResponse[$index] = $reason->getMessage();
            },
        ]);
        $pool->promise()->wait();
    }

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }
}
