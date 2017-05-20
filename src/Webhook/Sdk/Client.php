<?php


namespace Webhook\Sdk;


use GuzzleHttp\RequestOptions;

/**
 * Class Client
 * @package Webhook\Sdk
 */
class Client
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /**
     * Client constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->client = new \GuzzleHttp\Client($options);
    }

    /**
     * @param RequestMessage $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestMessage $message)
    {
        $options = [
            RequestOptions::BODY => json_encode($message),
        ];
        if ($message->getQuery() !== null && !empty($message->getQuery())) {
            $options['query'] = $message->getQuery();
        }
        return $this->client->post('/message', $options);
    }

    /**
     * @param string $id
     * @return ResponseMessage
     */
    public function getMessage(string $id)
    {
        $response = $this->client->get('/message/' . $id);
        return ResponseMessage::fromJson($response->getBody()->getContents());
    }
}