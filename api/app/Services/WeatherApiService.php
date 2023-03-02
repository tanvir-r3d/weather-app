<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class WeatherApiService
{
    private const URL = "http://api.weatherapi.com/v1";
    private Client $client;
    private array $params = [];
    private array $headers = [];

    public function __construct()
    {
        $this->params['key'] = $_ENV['WEATHER_API_KEY'];
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
            $url = self::URL . '/current.json';
            return $this->getRequest(method: 'GET', url: $url, options: [
                'query' => $this->getParams(),
                'headers' => $this->getHeaders(),
            ]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }
}
