<?php
declare(strict_types=1);


namespace Webhook\Bundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webhook\Bundle\Event\MessageFromApiSentEvent;
use Webhook\Bundle\Events;
use Webhook\Bundle\Repository\MessageRepository;
use Webhook\Bundle\Service\StrategyFactory;
use Webhook\Domain\Model\Message;

/**
 * Class MessageFromApiSentEventListener
 * @package Webhook\Bundle\EventListener
 */
class MessageFromApiSentEventListener implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::MESSAGE_FROM_API_SENT_EVENT => 'handle',
        ];
    }

    /** @var StrategyFactory */
    private $factory;

    /** @var MessageRepository */
    private $repository;

    /**
     * MessageFromApiSentEventListener constructor.
     * @param StrategyFactory $factory
     * @param MessageRepository $repository
     */
    public function __construct(StrategyFactory $factory, MessageRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @param MessageFromApiSentEvent $event
     */
    public function handle(MessageFromApiSentEvent $event)
    {
        $data = $event->getData();
        /** @var StrategyFactory $factory */
        $factory = $this->factory;
        $message = new Message($data['url'], $data['body']);
        $message->setStrategy($factory->createStrategy($event->getDto()));
        $this->repository->save($message);
        $event->setMessage($message);
    }
}