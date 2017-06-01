<?php
declare(strict_types=1);


namespace Webhook\Bundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CleanerCommand
 *
 * @package Webhook\Bundle\Command
 */
class CleanerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('webhooks:clean');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: get as parameter
        $dateTime = new \DateTime(); // get as parameter
        $this->getContainer()->get('webhook.repository')->clearOutdated($dateTime);
    }

}