<?php
declare(strict_types=1);


namespace Webhook\Bundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Webhook\Bundle\Controller\ParameterBag\Dto\AbstractStrategyParameterDto;
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

    /** @var AbstractStrategyParameterDto */
    private $dto;

    /**
     * MessageFromApiSentEvent constructor.
     * @param array $data
     * @param AbstractStrategyParameterDto $dto
     */
    public function __construct(array $data, AbstractStrategyParameterDto $dto = null)
    {
        $this->data = $data;
        $this->dto = $dto;
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
     * @return AbstractStrategyParameterDto
     */
    public function getDto(): ?AbstractStrategyParameterDto
    {
        return $this->dto;
    }
}