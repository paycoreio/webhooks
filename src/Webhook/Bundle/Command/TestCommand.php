<?php


namespace Webhook\Bundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Webhook\Domain\Model\Message;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('test:test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $producer = $this->getContainer()->get('amqp.producer');
        $m = new Message('http://httpbin.org/post2', '');
        $this->getContainer()->get('message.repository')->update($m);

        $producer->publish($m);
    }

}