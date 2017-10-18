<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service;

use Enqueue\Client\ProducerInterface;
use Webhook\Domain\Model\Webhook;

/**
 * Class WebhookProducer
 *
 * @package Webhook\Bundle\Service
 */
final class WebhookProducer
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
     * @param Webhook $message
     */
    public function publish(Webhook $message)
    {
        $this->producer->sendCommand(WebhookProcessor::NAME, $message->getId());
    }

}