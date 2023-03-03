<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class WeatherApiService
{
    private const URL = "https://weatherbit-v1-mashape.p.rapidapi.com/current";
    private const HOST = "weatherbit-v1-mashape.p.rapidapi.com";
    private Client $client;
    private array $params = [];
    private array $headers = [];

    public function __construct()
    {
        $this->headers['X-RapidAPI-Key'] = $_ENV['WEATHER_API_KEY'];
        $this->headers['X-RapidAPI-Host'] = self::HOST;
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

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }
}
