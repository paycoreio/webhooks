<?php
declare(strict_types=1);


namespace Webhook\Bundle\EventListener;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webhook\Bundle\Event\WebhookEvent;
use Webhook\Bundle\WebhookEvents;
use Webhook\Domain\Model\Webhook;

final class WebhookCallbackListener implements EventSubscriberInterface
{
    /** @var Client */
    private $client;

    public function __construct(Client $client = null)
    {
        if (null === $client) {
            $client = new Client([RequestOptions::TIMEOUT => 10]);
        }

        $this->client = $client;
    }


    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            WebhookEvents::WEBHOOK_DONE => 'handle',
            WebhookEvents::WEBHOOK_FAIL => 'handle',
        ];
    }

    public function handle(WebhookEvent $event)
    {
        $webhook = $event->getWebhook();

        if (null === $webhook->getCallbackUrl()) {
            return;
        }

        $request = $this->createRequest($webhook);

        $this->client->send($request);
    }

    /**
     * @param $webhook
     *
     * @return Request
     */
    private function createRequest(Webhook $webhook): Request
    {
        $headers['Content-Type'] = 'application/json';

        $data = [
            'id'        => $webhook->getId(),
            'status'    => $webhook->getStatus(),
            'processed' => $webhook->getProcessed()->format('U'),
            'metadata'  => $webhook->getMetadata(),
            'attempt'   => $webhook->getAttempt(),
        ];

        return new Request('POST', $webhook->getCallbackUrl(), $headers, json_encode($data));
    }
}