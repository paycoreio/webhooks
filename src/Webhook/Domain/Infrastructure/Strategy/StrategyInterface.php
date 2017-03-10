<?php


namespace Webhook\Infrastructure\Strategy;

use Webhook\Domain\Model\Message;


/**
 * Interface StrategyInterface.
 */
interface StrategyInterface
{
    /**
     * @param Message $message
     *
     * @return mixed
     */
    public function process(Message $message);
}