<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service;


use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Webhook\Domain\Infrastructure\HandlerInterface;
use Webhook\Domain\Model\Webhook;

/**
 * Class RetryHandler
 *
 * @package Webhook\Bundle\Service
 */
final class RetryHandler implements HandlerInterface
{
    /** @var ProducerInterface */
    private $producer;

    /**
     * RatesCollector constructor.
     *
     * @param ProducerInterface $producer
     */
    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @param Webhook $webhook
     *
     * @return void
     */
    public function handle(Webhook $webhook)
    {
        $id = $webhook->getId();

        $delay = $webhook->getNextAttempt()->getTimestamp() - time();

        $message = new Message($id);
        $message->setDelay($delay);

        $this->producer->sendCommand(WebhookProcessor::NAME, $message);
    }
}