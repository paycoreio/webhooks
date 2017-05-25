<?php
declare(strict_types=1);


namespace Webhook\Bundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Webhook\Domain\Model\Message;

/**
 * Class TestCommand
 *
 * @package Webhook\Bundle\Command
 */
class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('test:test');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $producer = $this->getContainer()->get('amqp.producer');
        $m = new Message('http://httpbin.org/post', '');
        $this->getContainer()->get('message.repository')->update($m);

        $producer->publish($m);
    }

}