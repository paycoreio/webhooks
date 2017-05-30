<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure;

use Webhook\Domain\Model\Webhook;

interface HandlerInterface
{
    public function handle(Webhook $webhook);
}