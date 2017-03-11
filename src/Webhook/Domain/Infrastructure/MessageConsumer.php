<?php


namespace Webhook\Domain\Infrastructure;

use Webhook\Domain\Model\Message;
use Webhook\Domain\Repository\MessageRepositoryInterface;

final class MessageConsumer
{
    /** @var HandlerInterface */
    private $handler;

    /** @var HandlerInterface */
    private $retryHandler;

    /** @var MessageRepositoryInterface */
    private $repository;

    /**
     * @param HandlerInterface $handler
     * @param HandlerInterface $retryHandler
     * @param MessageRepositoryInterface $repository
     */
    public function __construct(HandlerInterface $handler, HandlerInterface $retryHandler, MessageRepositoryInterface $repository)
    {
        $this->handler = $handler;
        $this->retryHandler = $retryHandler;
        $this->repository = $repository;
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function consume($id)
    {
        $message = $this->repository->get($id);
        /** @var RequestResult $r */
        $r = $this->handler->handle($message);

        if (!$r->isSuccess()) {
            $this->retryHandler->handle($message);
        }

        $message->setStatus(Message::STATUS_DONE);
        $this->repository->update($message);
    }
}