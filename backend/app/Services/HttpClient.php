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
                'json' => $body,
            ]);

            return json_decode(
                (string) $response->getBody(),
                true
            );

        } catch (RequestException $e) {

            $response = $e->getResponse();

            if ($response !== null) {

                throw new \Exception(
                    "HTTP {$response->getStatusCode()}:\n\n" .
                    (string) $response->getBody()
                );

            }

            throw new \Exception($e->getMessage());

        } catch (\Throwable $e) {

            throw new \Exception($e->getMessage());

        }

    }
}
