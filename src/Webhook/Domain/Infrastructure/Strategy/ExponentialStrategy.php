<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure\Strategy;


/**
 * Class ExponentialStrategy.
 */
final class ExponentialStrategy extends AbstractStrategy
{
    /** @var int */
    protected $interval;

    /** @var float */
    protected $base;

    /**
     * @param int $interval
     * @param float $base
     */
    public function __construct(int $interval = 5, float $base = 2.0)
    {
        $this->setInterval($interval);
        $this->setBase($base);
    }

    /**
     * @param int $interval
     */
    public function setInterval($interval)
    {
        if (!is_int($interval) || (int) $interval < 0) {
            throw new \InvalidArgumentException('Interval should be positive integer');
        }

        $this->interval = (int) $interval;
    }

    /**
     * @param $base
     */
    public function setBase($base)
    {
        if (!is_numeric($base) || (float) $base < 0) {
            throw new \InvalidArgumentException('Base should be positive float');
        }

        $this->base = (float) $base;
    }

    /**
     * @param int $attempt
     *
     * @return int
     */
    public function process(int $attempt): int
    {
        return (int) ceil($this->interval + ($this->base ** $attempt));
    }
}