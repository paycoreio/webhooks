<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Webhook\Domain\Model\Webhook;

final class Handler implements HandlerInterface
{
    /** @var  string */
    protected $defaultClient = 'Webhook-Client';

    /** @var Client|null */
    protected $client;

    public function __construct($client = null)
    {
        if (null === $client) {
            $client = new Client([
                RequestOptions::TIMEOUT => 10,
                RequestOptions::ALLOW_REDIRECTS => false,
            ]);
        }

        $this->client = $client;
    }

    /**
     * @param Webhook $webhook
     *
     * @return RequestResult
     */
    public function handle(Webhook $webhook)
    {
        $result = RequestResult::success();

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->post($webhook->getUrl(), $this->createOptions($webhook));

            if ($response->getStatusCode() !== $webhook->getExpectedCode()) {
                return RequestResult::codeMissMatch($response->getStatusCode());
            }

            if ($webhook->getExpectedContent() !== null
                && strpos($response->getBody()->getContents(), $webhook->getExpectedContent()) === false
            ) {
                return RequestResult::contentMissMatch();
            }

        } catch (TransferException $e) {
            $result = RequestResult::transportError($e->getMessage());
        }

        return $result;
    }

    /**
     * @param Webhook $webhook
     *
     * @return array
     */
    private function createOptions(Webhook $webhook): array
    {
        $options = [];

        $headers = [
            'Next-Retry'  => $webhook->getNextAttempt()->format('U'),
            'Retry-Count' => $webhook->getAttempt(),
            'User-Agent'  => $webhook->getUserAgent() ? $webhook->getUserAgent() : $this->defaultClient
        ];

        if ($webhook->isRaw()) {
            $headers['Content-Type'] = 'application/json';
            $options[RequestOptions::BODY] = $webhook->getBody();
        } else {
            $options[RequestOptions::FORM_PARAMS] = json_decode($webhook->getBody(), true);
        }

        $options[RequestOptions::HEADERS] = $headers;

        return $options;
    }
}