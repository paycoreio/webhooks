<?php
declare(strict_types=1);


namespace Webhook\Sdk;

/**
 * Class RequestMessage
 *
 * @package Webhook\Sdk
 */
class RequestMessage implements \JsonSerializable
{
    /** @var string */
    private $url;

    /** @var string */
    private $body;

    /** @var  array */
    private $strategy;

    /**
     * Message constructor.
     *
     * @param string $url
     * @param string $body
     */
    public function __construct(string $url, string $body)
    {
        $this->url = $url;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $strategy
     * @param array $options
     */
    public function setStrategy(string $strategy, array $options = [])
    {
        $this->strategy['name'] = $strategy;
        $this->strategy['options'] = $options;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'url'      => $this->url,
            'body'     => $this->body,
            'strategy' => $this->strategy
        ];
    }
}