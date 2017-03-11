<?php


namespace Webhook\Domain\Infrastructure;

use Webhook\Domain\Model\Message;

interface HandlerInterface
{
    public function handle(Message $message);
}