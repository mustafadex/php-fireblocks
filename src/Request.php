<?php

namespace Mustafadex\PhpFireblocks;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mustafadex\PhpFireblocks\Objects\Vault;

/**
 *
 */
class Request
{
    private $client;
    private $signer;
    private string $baseUrl = "https://api.fireblocks.io";

    /**
     * @param $apiKey
     * @param $secretKey
     */
    public function __construct($apiKey, $secretKey)
    {
        $this->client = new Client();
        $this->signer = new Signer($apiKey, $secretKey);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param $body
     * @param array $options
     * @return mixed
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, $body = null, array $options = [], $class = null) {
        $token = $this->signer->getToken($uri, $body);

        $url = $this->baseUrl . $uri;
        $finalOptions = array_merge($options, [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $token),
                "accept" => "application/json",
                "content-type" => 'application/json',
                'X-API-Key' => $this->signer->getApiKey(),
            ]
        ]);

        $response = $this->client->request($method, $url, $finalOptions);

        $data = json_decode($response->getBody());
        return $class == null ? $data : $this->mapObject($data, $class);
    }

    public function get(string $uri, $body = null, array $options = [], $class = null)
    {
        return $this->request('GET', $uri, $body, $options, $class);
    }

    public function post(string $uri, $body = null, array $options = [], $class = null)
    {
        return $this->request('POST', $uri, $body, $options, $class);
    }

    protected function mapObject($data, $class) {
        if (is_array($data)){
            foreach ($data as &$item) {
                if (is_object($item)) {
                    $item = $class::cast($item);
                }
            }
        }

        if (is_object($data)) {
            $data = $class::cast($data);
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }
}