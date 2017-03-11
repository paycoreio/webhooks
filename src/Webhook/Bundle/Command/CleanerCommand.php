<?php


namespace Webhook\Bundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('webhooks:clean');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: get as parameter
        $dateTime = new \DateTime(); // get as parameter
        $this->getContainer()->get('message.repository')->clearOutdated($dateTime);
    }

}