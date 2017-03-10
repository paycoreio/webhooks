<?php


namespace Webhook\Domain\Model;


use Ramsey\Uuid\Uuid;

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
    private $body = []; // json or array, because we want raw send

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

    private $attempts = 0;

    private $expectedCode = 200;

    private $expectedContent;

    /** @var  string */
    private $userAgent;

    public function __construct($url, $body)
    {
        $this->id = Uuid::getFactory()->uuid4()->toString();
        $this->url = $url;
        $this->body = $body;
        $this->created = new \DateTime();
        $this->status = 'NEW';
    }

    public function retry()
    {
        $this->attempts++;
    }

    /**
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * @return \DateTime
     */
    public function getNextAttempt(): \DateTime
    {
        return $this->nextAttempt;
    }

    /**
     * @param \DateTime $nextAttempt
     */
    public function setNextAttempt(\DateTime $nextAttempt)
    {
        $this->nextAttempt = $nextAttempt;
    }

    /**
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
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
     * @return mixed
     */
    public function getExpectedContent()
    {
        return $this->expectedContent;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param bool $raw
     */
    public function setRaw(bool $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @param mixed $expectedContent
     */
    public function setExpectedContent($expectedContent)
    {
        $this->expectedContent = $expectedContent;
    }
}