<?php
declare(strict_types=1);


namespace Webhook\Domain\Model;

use Ramsey\Uuid\Uuid;
use Webhook\Domain\Infrastructure\Strategy\AbstractStrategy;
use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;
use Webhook\Domain\Infrastructure\Strategy\StrategyInterface;

/**
 * Class Webhook
 *
 * @package Webhook\Domain\Model
 */
class Webhook implements \JsonSerializable
{
    const STATUS_QUEUED = 'queued';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';
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
    private $body;
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
     * Time at what webhook was processed to final state OK|FAIL
     *
     * @var \DateTime|null
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

    /** @var  string|null */
    private $expectedContent;

    /** @var  string|null */
    private $userAgent;

    /** @var  StrategyInterface|AbstractStrategy */
    private $strategy;

    /** @var  string|null */
    private $statusDetails;

    /** @var  array */
    private $metadata = [];

    /** @var  null|string */
    private $callbackUrl;

    /**
     * Webhook constructor.
     *
     * @param string $url
     * @param $body
     */
    public function __construct(string $url, $body)
    {
        $this->id = Uuid::getFactory()->uuid4()->toString();
        $this->url = $url;
        $this->body = $body;
        $this->created = new \DateTime();
        $this->status = self::STATUS_QUEUED;
        $this->setStrategy(new LinearStrategy(60));
    }

    public function retry()
    {
        $this->attempt++;
        $this->calculateNextRetry();

        if ($this->attempt === $this->maxAttempts) {
            $this->status = self::STATUS_FAILED;
            $this->processed();
        } else {
            $this->status = self::STATUS_QUEUED;
        }
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

    private function processed()
    {
        $this->processed = new \DateTime();
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

    /**
     * @param bool $raw
     */
    public function setRaw(bool $raw)
    {
        $this->raw = $raw;
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
    public function getProcessed(): \DateTime
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = [];
        foreach (get_object_vars($this) as $k => $v) {
            if ($v instanceof \DateTime) {
                $v = (int)$v->format('U');
            }

            $result[$k] = $v;
        }

        return $result;
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

    /**
     * @return string
     */
    public function getStatusDetails(): string
    {
        return $this->statusDetails;
    }

    /**
     * @param string $statusDetails
     */
    public function setStatusDetails(string $statusDetails)
    {
        $this->statusDetails = $statusDetails;
    }

    /**
     * @return AbstractStrategy|StrategyInterface
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param StrategyInterface $strategy
     */
    public function setStrategy(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
        $this->calculateNextRetry();
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return null|string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * @param null|string $callbackUrl
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }
}