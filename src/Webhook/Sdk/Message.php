<?php


namespace Webhook\Sdk;


class Message
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $status;

    /** @var array */
    private $body = [];
    /**
     * We can send body as raw json or array form data
     *
     * @var bool
     */
    private $raw = true;
    /**
     * @var \DateTime
     */
    private $created;
    /**
     * Time at what message was processed to final state OK|FAIL
     *
     * @var \DateTime
     */
    private $processed;
    /**
     * @var \DateTime
     */
    private $nextAttempt;

    /** @var int */
    private $attempt = 1;

    /** @var int */
    private $maxAttempts = 10;

    /** @var int */
    private $expectedCode = 200;

    /** @var  string */
    private $expectedContent;

    /** @var  string */
    private $userAgent;

    /** @var  string */
    private $strategy;

    /** @var  string */
    private $statusDetails;

    public function __construct(array $options)
    {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }

    public static function fromJson($json)
    {
        $array = json_decode($json, true);

        return new static($array);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $body
     */
    public function setBody(array $body)
    {
        $this->body = $body;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @return int
     */
    public function getExpectedCode(): int
    {
        return $this->expectedCode;
    }

    /**
     * @param int $expectedCode
     */
    public function setExpectedCode(int $expectedCode)
    {
        $this->expectedCode = $expectedCode;
    }

    /**
     * @return string
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return string
     */
    public function getExpectedContent(): ?string
    {
        return $this->expectedContent;
    }

    /**
     * @param string $expectedContent
     */
    public function setExpectedContent(string $expectedContent)
    {
        $this->expectedContent = $expectedContent;
    }

    /**
     * @return \DateTime
     */
    public function getProcessed(): ?\DateTime
    {
        return $this->processed;
    }

    /**
     * @return \DateTime
     */
    public function getNextAttempt(): ?\DateTime
    {
        return $this->nextAttempt;
    }

    /**
     * @return string
     */
    public function getStatusDetails(): ?string
    {
        return $this->statusDetails;
    }

    /**
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }

    /**
     * @param bool $raw
     */
    public function setRaw(bool $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    /**
     * @param int $maxAttempts
     */
    public function setMaxAttempts(int $maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * @return int
     */
    public function getAttempt(): int
    {
        return $this->attempt;
    }

    public function toJson()
    {

    }
}