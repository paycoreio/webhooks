<?php


namespace Webhook\Domain\Infrastructure\Strategy;

/**
 * Class LinearStrategy.
 */
final class LinearStrategy extends AbstractStrategy
{
    /** @var int */
    private $interval;

    /** @var int */
    private $multiplier;

    /**
     * @param int $interval
     * @param int $multiplier
     */
    public function __construct(int $interval = 5, int $multiplier = 1)
    {
        if ($interval < 0 || $multiplier < 0) {
            throw new \InvalidArgumentException('Interval and multiplier should be positive integers.');
        }
        $this->interval = $interval;
        $this->multiplier = $multiplier;
    }

    /**
     *
     * @param int $attempt
     *
     * @return int
     */
    public function process(int $attempt): int
    {
        return $this->interval * $this->multiplier * $attempt;
    }
}