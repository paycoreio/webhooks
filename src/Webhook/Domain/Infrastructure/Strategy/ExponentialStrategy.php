<?php


namespace Webhook\Infrastructure\Strategy;

use Webhook\Domain\Model\Message;


/**
 * Class ExponentialStrategy.
 */
class ExponentialStrategy extends AbstractStrategy
{
    /**
     * @var int
     */
    private $interval;
    /**
     * @var float
     */
    private $base;

    /**
     * ExponentialStrategy constructor.
     *
     * @param int   $interval
     * @param float $base
     */
    public function __construct(int $interval = 10, float $base = 1.5)
    {
        if ($interval < 0 || $base < 0) {
            throw new \RuntimeException('Interval and base should be positive numbers.');
        }
        $this->interval = $interval;
        $this->base = $base;
    }

    /**
     * @param Message $message
     *
     * @return int
     */
    protected function compute(Message $message)
    {
        return (int) ceil($this->interval + pow($this->base, $message->getAttempts() + 1));
    }
}