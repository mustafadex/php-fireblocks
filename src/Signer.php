<?php

namespace Mustafadex\PhpFireblocks;
use Firebase\JWT\JWT;

class Signer
{
    private const EXPIRATION_IN_SECONDS = 30;
    private const JWT_ALGO              = 'RS256';
    private const BODY_ALGO             = 'sha256';
    private const MINIMUM_NONCE         = 1000;
    private string $apiKey;
    private string $secretKey;

    /**
     * @param $apiKey
     * @param $secretKey
     */
    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }


    /**
     * @param string $uri
     * @param $body
     * @return array
     */
    protected function getPayload(string $uri, $body = null): array
    {
        $time = time();

        $payload = [
            'uri'   => $uri,
            'nonce' => rand(self::MINIMUM_NONCE, getrandmax()),
            'iat'   => $time,
            'exp'   => $time + self::EXPIRATION_IN_SECONDS,
            'sub'   => $this->apiKey,
        ];

        if (is_array($body) || $body instanceof \JsonSerializable) {
            $payload['bodyHash'] = hash(self::BODY_ALGO, json_encode($body));
        }

        return $payload;
    }

    /**
     * @param string $uri
     * @param $body
     * @return string
     */
    public function getToken(string $uri, $body = null): string
    {
        $payload = $this->getPayload($uri,$body);
        $secret = $this->secretKey;
        return JWT::encode($payload, $secret, self::JWT_ALGO);
    }

}