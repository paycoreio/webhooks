<?php


namespace Webhook\Infrastructure\Strategy;

use Webhook\Domain\Model\Message;


/**
 * Class LinearStrategy.
 */
class LinearStrategy extends AbstractStrategy
{
    /**
     * @var int
     */
    private $interval;
    /**
     * @var int
     */
    private $multiplier;

    /**
     * LinearStrategy constructor.
     *
     * @param int $interval
     * @param int $multiplier
     */
    public function __construct(int $interval = 5, int $multiplier = 1)
    {
        if ($interval < 0 || $multiplier < 0) {
            throw new \RuntimeException('Interval and multiplier should be positive integers.');
        }
        $this->interval = $interval;
        $this->multiplier = $multiplier;
    }

    /**
     * @param Message $message
     *
     * @return int
     */
    protected function compute(Message $message)
    {
        return $this->interval * $this->multiplier * ($message->getAttempts() + 1);
    }
}