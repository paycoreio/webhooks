<?php


namespace Webhook\Bundle\Service;


use Webhook\Domain\Infrastructure\HandlerInterface;
use Webhook\Domain\Model\Message;
use Bunny\Client;


final class RetryHandler implements HandlerInterface
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $queueName;

    public function __construct(Client $client, string $queue)
    {
        $this->client = $client;
        $this->queueName = $queue;
    }

    public function handle(Message $message)
    {
        $id = $message->getId();

        $channel = $this->client->channel();

        $channel->exchangeDeclare($this->queueName, 'x-delayed-message', false, true, false, false, false,
            ['x-delayed-type' => 'direct']
        );

        $delay = ($message->getNextAttempt()->format('U') - time()) * 1000;

        dump('Delay on ' . $delay);

        $channel->publish($id, ['x-delay' => $delay], $this->queueName);
    }
}