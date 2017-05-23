<?php
declare(strict_types=1);


namespace Webhook\Bundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webhook\Sdk\Client;
use Webhook\Sdk\RequestMessage;

/**
 * Class TestSdkCommand
 *
 * @package Webhook\Bundle\Command
 */
class TestSdkCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('sdk:test');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sdkClient = new Client([
            'base_uri' => 'http://localhost:8080/app_dev.php',
        ]);
        $requestMessage = new RequestMessage('http://httpbin.org/post', 'test message');
        $requestMessage->setStrategy('exponential');
        $sdkClient->send($requestMessage);
    }
}