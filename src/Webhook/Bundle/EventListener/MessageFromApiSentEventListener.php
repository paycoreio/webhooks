<?php
declare(strict_types=1);


namespace Webhook\Bundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webhook\Bundle\Event\MessageFromApiSentEvent;
use Webhook\Bundle\Events;
use Webhook\Bundle\Repository\MessageRepository;
use Webhook\Bundle\Service\StrategiesMapper;
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

    /** @var MessageRepository */
    private $repository;

    /** @var StrategiesMapper */
    private $mapper;

    /**
     * MessageFromApiSentEventListener constructor.
     * @param MessageRepository $repository
     * @param StrategiesMapper $strategiesMapper
     */
    public function __construct(
        MessageRepository $repository,
        StrategiesMapper $strategiesMapper)
    {
        $this->repository = $repository;
        $this->mapper = $strategiesMapper;
    }

    /**
     * @param MessageFromApiSentEvent $event
     */
    public function handle(MessageFromApiSentEvent $event)
    {
        $data = $event->getData();
        $message = new Message($data['url'], $data['body']);
        $bag = $event->getBag();
        $strategy = $this->mapper->getStrategyInstance($bag->getStrategyAlias());
        $strategy->setOptions($bag->getOptions());
        $message->setStrategy($strategy);
        $this->repository->save($message);
        $event->setMessage($message);
    }
}