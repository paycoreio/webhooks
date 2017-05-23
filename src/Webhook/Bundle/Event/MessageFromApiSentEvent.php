<?php
declare(strict_types=1);


namespace Webhook\Bundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Webhook\Bundle\Controller\ParameterBag\StrategyParameterBag;
use Webhook\Domain\Model\Message;

/**
 * Class MessageFromApiSentEvent
 * @package Webhook\Bundle\Event
 */
final class MessageFromApiSentEvent extends Event
{
    /** @var Message */
    private $message;

    /** @var array */
    private $data;

    /** @var StrategyParameterBag */
    private $bag;

    /**
     * MessageFromApiSentEvent constructor.
     * @param array $data
     * @param StrategyParameterBag $bag
     * @internal param array $query
     * @internal param AbstractStrategyParameterDto $dto
     */
    public function __construct(array $data, StrategyParameterBag $bag)
    {
        $this->data = $data;
        $this->bag = $bag;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return StrategyParameterBag
     */
    public function getBag(): StrategyParameterBag
    {
        return $this->bag;
    }
}