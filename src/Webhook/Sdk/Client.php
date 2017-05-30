<?php
declare(strict_types=1);


namespace Webhook\Sdk;


use GuzzleHttp\RequestOptions;

/**
 * Class Client
 *
 * @package Webhook\Sdk
 */
class Client
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /**
     * Client constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->client = new \GuzzleHttp\Client($options);
    }

    /**
     * @param RequestWebhook $webhook
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestWebhook $webhook)
    {
        $options = [
            RequestOptions::BODY => json_encode($webhook),
        ];

        return $this->client->post('/webhooks', $options);
    }

    /**
     * @param string $id
     *
     * @return ResponseWebhook
     */
    public function getMessage(string $id)
    {
        $response = $this->client->get('/webhooks/' . $id);
        return ResponseWebhook::fromJson($response->getBody()->getContents());
    }
}