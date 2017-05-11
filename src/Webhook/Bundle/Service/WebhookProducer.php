<?php


namespace Webhook\Bundle\Service;

use Bunny\Client;
use Webhook\Domain\Model\Message;

class WebhookProducer
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $queue;

    public function __construct(Client $client, string $queue)
    {
        $this->client = $client;
        $this->queue = $queue;
    }

    /**
     * @param Message $message
     */
    public function publish(Message $message)
    {
        $id = $message->getId();
        $channel = $this->client->channel();
        $channel->queueDeclare($this->queue, false, false, false, false, false,
            ['x-delayed-type' => "direct"]
        );

        $channel->publish($id, [], '', $this->queue);
    }

}