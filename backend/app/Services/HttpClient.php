<?php

namespace PromptLens\Services;

use GuzzleHttp\Client;

class HttpClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 120,
        ]);
    }

    public function postJson(
        string $url,
        array $headers,
        array $body
    ): array {

        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }
}
