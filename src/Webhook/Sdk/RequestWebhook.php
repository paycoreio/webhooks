<?php
declare(strict_types=1);


namespace Webhook\Sdk;

/**
 * Class RequestMessage
 *
 * @package Webhook\Sdk
 */
class RequestWebhook implements \JsonSerializable
{
    /** @var string */
    private $url;

    /** @var string|array */
    private $body;

    /** @var bool */
    private $raw = true;

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

    /** @var  string */
    private $callbackUrl;

    /**
     * Message constructor.
     *
     * @param string $url
     * @param string|array $body
     */
    public function __construct(string $url, $body)
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
     * @return string|array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $strategy
     * @param array $options
     *
     * @return $this
     */
    public function setStrategy(string $strategy, array $options = [])
    {
        $this->strategy['name'] = $strategy;
        $this->strategy['options'] = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param int $maxAttempts
     *
     * @return $this
     */
    public function setMaxAttempts(int $maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;

        return $this;
    }

    /**
     * @param int $expectedCode
     *
     * @return $this
     */
    public function setExpectedCode(int $expectedCode)
    {
        $this->expectedCode = $expectedCode;

        return $this;
    }

    /**
     * @param string $expectedContent
     *
     * @return $this
     */
    public function setExpectedContent(string $expectedContent)
    {
        $this->expectedContent = $expectedContent;

        return $this;
    }

    /**
     * @param string $userAgent
     *
     * @return $this
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @param string $callbackUrl
     *
     * @return $this
     */
    public function setCallbackUrl(string $callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    /**
     * @return $this
     */
    public function asForm()
    {
        if (!is_array($this->body)) {
            throw new \RuntimeException('If you want to send body as form data, you should set body as array.');
        }

        $this->raw = false;

        return $this;
    }
}