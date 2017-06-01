<?php
declare(strict_types=1);


namespace Webhook\Bundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Webhook\Domain\Model\Webhook;

/**
 * Class WebhookEvent
 *
 * @package Webhook\Bundle\Event
 */
final class WebhookEvent extends Event
{
    /** @var  Webhook */
    private $webhook;

    /**
     * WebhookEvent constructor.
     *
     * @param Webhook $webhook
     */
    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    /**
     * @return Webhook
     */
    public function getWebhook(): Webhook
    {
        return $this->webhook;
    }

}