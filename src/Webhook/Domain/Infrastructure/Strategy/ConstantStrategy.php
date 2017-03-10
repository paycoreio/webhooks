<?php


namespace Webhook\Infrastructure\Strategy;

use Webhook\Domain\Model\Message;


/**
 * Class ConstantStrategy.
 */
class ConstantStrategy extends AbstractStrategy
{
    const DEFAULT_INTERVAL = 5;
    /**
     * @var int
     */
    private $interval;

    /**
     * ConstantStrategy constructor.
     *
     * @param int $interval
     */
    public function __construct(int $interval = self::DEFAULT_INTERVAL)
    {
        $this->interval = $interval;
    }

    /**
     * @param Message $message
     *
     * @return int
     */
    protected function compute(Message $message)
    {
        return $this->interval;
    }
}