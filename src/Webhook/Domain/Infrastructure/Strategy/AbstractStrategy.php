<?php


namespace Webhook\Infrastructure\Strategy;

use Webhook\Domain\Model\Message;


/**
 * Class AbstractStrategy.
 */
abstract class AbstractStrategy implements StrategyInterface
{
    /**
     * @param Message $message
     *
     * @return mixed|void
     */
    public function process(Message $message)
    {
        $interval = $this->compute($message);
        $nextAttempt = time() + $interval;
        $message->setNextAttempt(new \DateTime('@' . $nextAttempt)); // @ is needed, because of integer argument
    }

    /**
     * @param Message $message
     *
     * @return int
     */
    abstract protected function compute(Message $message);
}