<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Webhook\Bundle\Event\WebhookEvent;
use Webhook\Bundle\WebhookEvents;
use Webhook\Domain\Infrastructure\HandlerInterface;
use Webhook\Domain\Infrastructure\RequestResult;
use Webhook\Domain\Model\Webhook;
use Webhook\Domain\Repository\WebhookRepositoryInterface;

/**
 * Class WebhookConsumer
 *
 * @package Webhook\Domain\Infrastructure
 */
final class WebhookConsumer
{
    /** @var HandlerInterface */
    private $handler;

    /** @var WebhookRepositoryInterface */
    private $repository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param HandlerInterface $handler
     * @param WebhookRepositoryInterface $repository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        HandlerInterface $handler,
        WebhookRepositoryInterface $repository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->handler = $handler;
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function consume($id)
    {
        $webhook = $this->repository->get($id);

        if (null !== $webhook) {
            if ($webhook->getStatus() === Webhook::STATUS_FAILED) {
                $this->notifyFail($webhook);
                return;
            }
            /** @var RequestResult $r */
            $r = $this->handler->handle($webhook);
            $webhook->setStatusDetails($r->getDetails());

            if (!$r->isSuccess()) {
                $webhook->retry();
                $this->notifyRetry($webhook);
            } else {
                $webhook->done();
                $this->notifyDone($webhook);
            }

            $this->repository->update($webhook);
        }
    }

    private function notifyFail(Webhook $webhook)
    {
        $this->notify(WebhookEvents::WEBHOOK_FAIL, $webhook);
    }

    private function notify(string $event, Webhook $webhook)
    {
        $this->dispatcher->dispatch($event, new WebhookEvent($webhook));
    }

    private function notifyRetry(Webhook $webhook)
    {
        $this->notify(WebhookEvents::WEBHOOK_RETRY, $webhook);
    }

    private function notifyDone(Webhook $webhook)
    {
        $this->notify(WebhookEvents::WEBHOOK_DONE, $webhook);
    }
}