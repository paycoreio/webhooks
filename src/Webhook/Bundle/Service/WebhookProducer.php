<?php


namespace Webhook\Bundle\Service;

use Bunny\Client;
use Webhook\Domain\Model\Message;

final class WebhookProducer
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $queueName;

    /**
     * WebhookProducer constructor.
     *
     * @param Client $client
     * @param string $queue
     */
    public function __construct(Client $client, string $queue)
    {
        $this->client = $client;
        $this->queueName = $queue;
    }

    /**
     * @param Message $message
     */
    public function publish(Message $message)
    {
        $id = $message->getId();
        $channel = $this->client->channel();

        $channel->exchangeDeclare($this->queueName, 'x-delayed-message', false, true, false, false, false,
            ['x-delayed-type' => 'direct']
        );

        $channel->publish($id, [], $this->queueName);
    }

}