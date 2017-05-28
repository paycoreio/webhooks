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
            'base_uri' => 'http://localhost',
        ]);
        $requestMessage = new RequestMessage('http://httpbin.org/post', 'test message');
        $requestMessage->setStrategy('exponential');
        $requestMessage->setExpectedContent('test');
        $requestMessage->setUserAgent('firefox');

        $sdkClient->send($requestMessage);
    }
}