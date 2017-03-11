<?php


namespace Webhook\Domain\Model;

use Ramsey\Uuid\Uuid;
use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;
use Webhook\Domain\Infrastructure\Strategy\StrategyInterface;

class Message implements \JsonSerializable
{
    const STATUS_QUEUED = 'queued';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';
    /**
     * @var string
     */
    private $id; // json or array, because we want raw send
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

    /** @var  StrategyInterface */
    private $strategy;

    public function __construct(string $url, $body)
    {
        $this->id = Uuid::getFactory()->uuid4()->toString();
        $this->url = $url;
        $this->body = $body;
        $this->created = new \DateTime();
        $this->status = self::STATUS_QUEUED;
        $this->strategy = new LinearStrategy(60);
        $this->calculateNextRetry();
    }

    private function calculateNextRetry()
    {
        $interval = $this->strategy->process($this->attempt);
        $int = new \DateInterval('PT' . $interval . 'S');
        $nextAttempt = (new \DateTime())->add($int);

        $this->setNextAttempt($nextAttempt);
    }

    /**
     * @param \DateTime $nextAttempt
     */
    private function setNextAttempt(\DateTime $nextAttempt)
    {
        $this->nextAttempt = $nextAttempt;
    }

    public function setStrategy(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
        $this->calculateNextRetry();
    }

    public function retry()
    {
        $this->attempt++;

        if ($this->attempt == $this->maxAttempts) {
            $this->status = self::STATUS_FAILED;
        } else {
            $this->status = self::STATUS_QUEUED;
        }
    }

    /**
     * If data is raw we will send it as html form
     *
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }

    public function asJson()
    {
        $this->raw = false;
    }

    public function asForm()
    {
        $this->raw = true;
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
     * @param mixed $expectedContent
     */
    public function setExpectedContent($expectedContent)
    {
        $this->expectedContent = $expectedContent;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return \DateTime
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function done()
    {
        $this->status = self::STATUS_DONE;
        $this->processed();
    }

    private function processed()
    {
        $this->processed = new \DateTime();
    }

    public function jsonSerialize()
    {
        return [
            'status'     => $this->getStatus(),
            'created'    => $this->getCreated()->format('U'),
            'attempt'    => $this->getAttempt(),
            'next-retry' => $this->getNextAttempt()->format('U'),
        ];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
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
    public function getAttempt(): int
    {
        return $this->attempt;
    }

    /**
     * @return \DateTime
     */
    public function getNextAttempt(): \DateTime
    {
        return $this->nextAttempt;
    }
}