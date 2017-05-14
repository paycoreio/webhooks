<?php


namespace Webhook\Sdk;


use GuzzleHttp\RequestOptions;

class Client
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct($options = [])
    {
        $this->client = new \GuzzleHttp\Client($options);
    }

    public function send(Message $message)
    {
        return $this->client->post('/message', [RequestOptions::JSON => json_encode($message)]);
    }

    public function getMessage(string $id)
    {
        $response = $this->client->get('/message/' . $id);

        return Message::fromJson($response->getBody()->getContents());

    }
}