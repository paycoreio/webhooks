<?php


namespace Webhook\Bundle\Service;


use Webhook\Domain\Infrastructure\HandlerInterface;
use Webhook\Domain\Model\Message;
use Bunny\Client;


class RetryHandler implements HandlerInterface
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

    public function handle(Message $message)
    {
        $id = $message->getId();

        $channel = $this->client->channel();
        $channel->queueDeclare($this->queue, false, false, false, false, false,
            ['x-delayed-type' => "direct"]
        );
        $delay = ($message->getNextAttempt()->format('U') - time()) * 1000;
        $channel->publish($id, ['x-delay' => $delay], '', $this->queue);
        $message->retry();
    }
}