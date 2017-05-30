<?php
declare(strict_types=1);


namespace Webhook\Bundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Webhook\Domain\Model\Webhook;

final class WebhookEvent extends Event
{
    /** @var  Webhook */
    private $webhook;

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