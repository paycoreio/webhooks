<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure;

use Webhook\Domain\Model\Message;

interface HandlerInterface
{
    public function handle(Message $message);
}