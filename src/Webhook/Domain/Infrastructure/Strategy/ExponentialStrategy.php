<?php


namespace Webhook\Domain\Infrastructure\Strategy;


/**
 * Class ExponentialStrategy.
 */
final class ExponentialStrategy extends AbstractStrategy
{
    /** @var int */
    protected $interval;

    /** @var float */
    protected  $base;

    /**
     * @param int $interval
     * @param float $base
     */
    public function __construct(int $interval = 10, float $base = 2.0)
    {
        if ($interval < 0 || $base < 0) {
            throw new \InvalidArgumentException('Interval and base should be positive numbers.');
        }

        $this->interval = $interval;
        $this->base = $base;
    }

    /**
     *
     * @param int $attempt
     *
     * @return int
     */
    public function process(int $attempt): int
    {
        return (int) ceil($this->interval + pow($this->base, $attempt));
    }
}