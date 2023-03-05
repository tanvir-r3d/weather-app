<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class WeatherApiService
{
    private const URL = "https://api.openweathermap.org/data/2.5";
    private Client $client;
    private array $params = [];
    private array $headers = [];

    public function __construct()
    {
        $this->params['appid'] = $_ENV['WEATHER_API_KEY'];
        $this->client = new Client([
            'base_url' => self::URL,
        ]);
    }

    /**
     * @return self
     */
    public static function init(): self
    {
        return new self();
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
     * @param float $lat
     * @param float $lon
     * @return WeatherApiService
     */
    public function setLocation(float $lat, float $lon): self
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
        return $this->getClient()->requestAsync($method, $url, $options)->wait();
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function fetchCurrent(): mixed
    {
        try {
            $url = self::URL . '/weather';
            $request = $this->getRequest(method: 'GET', url: $url, options: [
                'query' => $this->getParams(),
                'headers' => $this->getHeaders(),
            ]);
            $request = $request->getBody()->getContents();
            return json_decode($request, true);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function fetchForecast(): mixed
    {
        try {
            $url = self::URL . '/forecast';
            $request = $this->getRequest(method: 'GET', url: $url, options: [
                'query' => $this->getParams(),
                'headers' => $this->getHeaders(),
            ]);
            $request = $request->getBody()->getContents();
            return json_decode($request, true);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    public function __get($name)
    {
        return $this->params[$name];
    }
}
