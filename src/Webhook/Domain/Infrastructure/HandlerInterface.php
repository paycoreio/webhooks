<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure;

use Webhook\Domain\Model\Webhook;

/**
 * Interface HandlerInterface
 *
 * @package Webhook\Domain\Infrastructure
 */
interface HandlerInterface
{
    /**
     * @param Webhook $webhook
     *
     * @return mixed
     */
    public function handle(Webhook $webhook);
}