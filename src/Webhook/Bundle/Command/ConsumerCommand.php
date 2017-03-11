<?php


namespace Webhook\Bundle\Command;


use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('test:con');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bunny = $this->getContainer()->get('amqp.client');

        $channel = $bunny->channel();
        $queue = $this->getContainer()->getParameter('webhooks.queue');
        $channel->queueDeclare($queue);

        $consumer = $this->getContainer()->get('webhooks.consumer');

        $channel->run(
            function (Message $message, Channel $channel, Client $bunny) use ($consumer) {
                $consumer->consume($message->content);
                $channel->ack($message);
            },
            $queue
        );
    }

}