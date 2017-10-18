<?php
declare(strict_types=1);

namespace Webhook\Bundle\Service;


use Enqueue\Client\CommandSubscriberInterface;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

/**
 * Class WebhookProcessor
 *
 * @package Webhook\Bundle\Service
 */
final class WebhookProcessor implements PsrProcessor, CommandSubscriberInterface
{
    public const NAME = 'webhook-processor';

    /** @var WebhookConsumer */
    private $consumer;

    /**
     * RatesProcessor constructor.
     *
     * @param WebhookConsumer $consumer
     */
    public function __construct(WebhookConsumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * @param PsrMessage $message
     * @param PsrContext $context
     *
     * @return string
     */
    public function process(PsrMessage $message, PsrContext $context): string
    {
        $id = $message->getBody();

        $this->consumer->consume($id);

        return self::ACK;
    }

    /**
     * @return array
     */
    public static function getSubscribedCommand(): array
    {
        return [
            'processorName' => self::NAME,

            // these are optional, setting these option we make the migration smooth and backward compatible.
            'queueName' => 'webhooks',
            'queueNameHardcoded' => true,
            'exclusive' => true,
        ];
    }
}
