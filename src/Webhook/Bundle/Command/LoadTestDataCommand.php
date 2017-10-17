<?php
declare(strict_types=1);

namespace Webhook\Bundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webhook\Bundle\Repository\WebhookRepository;
use Webhook\Domain\Model\Webhook;

/**
 * Class LoadTestData
 *
 * @package Webhook\Bundle\Command
 */
class LoadTestDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('webhooks:load:data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var WebhookRepository $repository */
        $repository = $this->getContainer()->get('webhook.repository');

        for ($i = 1; $i <= 100; $i++) {
            $webhook = new Webhook('http://httpbin.org/status/500', 'body');
            $repository->save($webhook);
        }
    }
}