<?php


namespace Webhook\Bundle\Command;


use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsumerCommand
 *
 * @package Webhook\Bundle\Command
 */
class ConsumerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('webhooks:consume');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bunny = $this->getContainer()->get('amqp.client');

        $channel = $bunny->channel();
        $queueName = $this->getContainer()->getParameter('webhooks.queue');

        $channel->exchangeDeclare($queueName, 'x-delayed-message', false, true, false, false, false,
            ['x-delayed-type' => 'direct']
        );

        $queue = $channel->queueDeclare($queueName, false, false, true, false);
        $channel->queueBind($queue->queue, $queueName);

        $consumer = $this->getContainer()->get('webhooks.consumer');

        $channel->run(
            function (Message $message, Channel $channel, Client $bunny) use ($consumer) {
                $consumer->consume($message->content);
                $channel->ack($message);
            },
            $queueName
        );
    }

}