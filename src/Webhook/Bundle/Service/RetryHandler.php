<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service;


use Webhook\Domain\Infrastructure\HandlerInterface;
use Webhook\Domain\Model\Webhook;
use Bunny\Client;

/**
 * Class RetryHandler
 *
 * @package Webhook\Bundle\Service
 */
final class RetryHandler implements HandlerInterface
{
    /** @var Client */
    private $client;

    /** @var string */
    private $queueName;

    /**
     * RetryHandler constructor.
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
     * @param Webhook $webhook
     */
    public function handle(Webhook $webhook)
    {
        $id = $webhook->getId();

        $channel = $this->client->channel();

        $channel->exchangeDeclare($this->queueName, 'x-delayed-message', false, true, false, false, false,
            ['x-delayed-type' => 'direct']
        );

        $delay = ($webhook->getNextAttempt()->format('U') - time()) * 1000;

        dump('Delay on ' . $delay);

        $channel->publish($id, ['x-delay' => $delay], $this->queueName);
    }
}