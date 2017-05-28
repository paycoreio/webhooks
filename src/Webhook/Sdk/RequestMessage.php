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

    /** @var  int */
    private $maxAttempts;

    /** @var  int */
    private $expectedCode;

    /** @var  string */
    private $expectedContent;

    /** @var  string */
    private $userAgent;

    /** @var  array */
    private $metadata;

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
        return get_object_vars($this);
    }

    /**
     * @param int $maxAttempts
     */
    public function setMaxAttempts(int $maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * @param int $expectedCode
     */
    public function setExpectedCode(int $expectedCode)
    {
        $this->expectedCode = $expectedCode;
    }

    /**
     * @param string $expectedContent
     */
    public function setExpectedContent(string $expectedContent)
    {
        $this->expectedContent = $expectedContent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
    }
}