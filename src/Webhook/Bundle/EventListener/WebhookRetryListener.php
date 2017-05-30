<?php
declare(strict_types=1);


namespace Webhook\Bundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webhook\Bundle\Event\WebhookEvent;
use Webhook\Bundle\WebhookEvents;
use Webhook\Domain\Infrastructure\HandlerInterface;

final class WebhookRetryListener implements EventSubscriberInterface
{
    /** @var  HandlerInterface */
    private $retryHandler;

    public function __construct(HandlerInterface $retryHandler)
    {
        $this->retryHandler = $retryHandler;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            WebhookEvents::WEBHOOK_RETRY => 'handle',
        ];
    }

    public function handle(WebhookEvent $event)
    {
        $this->retryHandler->handle($event->getWebhook());
    }
}