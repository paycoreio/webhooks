<?php


namespace Webhook\Bundle\Service;


use Webhook\Domain\Infrastructure\HandlerInterface;
use Webhook\Domain\Model\Message;

class RetryHandler implements HandlerInterface
{

    public function handle(Message $message)
    {
        // TODO: Implement handle() method.
    }
}