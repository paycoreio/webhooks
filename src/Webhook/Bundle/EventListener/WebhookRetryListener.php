<?php
declare(strict_types=1);


namespace Webhook\Bundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webhook\Bundle\Event\WebhookEvent;
use Webhook\Bundle\WebhookEvents;
use Webhook\Domain\Infrastructure\HandlerInterface;

/**
 * Class WebhookRetryListener
 *
 * @package Webhook\Bundle\EventListener
 */
final class WebhookRetryListener implements EventSubscriberInterface
{
    /** @var  HandlerInterface */
    private $retryHandler;

    /**
     * WebhookRetryListener constructor.
     *
     * @param HandlerInterface $retryHandler
     */
    public function __construct(HandlerInterface $retryHandler)
    {
        $this->retryHandler = $retryHandler;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WebhookEvents::WEBHOOK_RETRY => 'handle',
        ];
    }

    /**
     * @param WebhookEvent $event
     */
    public function handle(WebhookEvent $event)
    {
        $this->retryHandler->handle($event->getWebhook());
    }
}