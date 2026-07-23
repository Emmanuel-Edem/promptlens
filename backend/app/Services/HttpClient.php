<?php

namespace PromptLens\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

        try {

            $response = $this->client->post($url, [
                'headers' => $headers,
                'json' => $body
            ]);

            return json_decode(
                $response->getBody()->getContents(),
                true
            );

        } catch (RequestException $e) {

            if ($e->hasResponse()) {

                $response = $e->getResponse();

                $body = (string) $response->getBody();

                throw new \Exception(
                    "HTTP {$response->getStatusCode()}: {$body}"
                );
            }

            throw new \Exception(
                "Request failed: " . $e->getMessage()
            );

        } catch (\Throwable $e) {

            throw new \Exception(
                "Unexpected error: " . $e->getMessage()
            );

        }
    }
}
