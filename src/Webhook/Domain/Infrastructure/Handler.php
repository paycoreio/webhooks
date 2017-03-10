<?php


namespace Webhook\Infrastructure;

use GuzzleHttp\Psr7\Request;
use Http\Client\Exception\HttpException;
use Http\Discovery\HttpClientDiscovery;
use Psr\Http\Message\ResponseInterface;
use Webhook\Domain\Model\Message;

class Handler
{
    /** @var \Http\Client\HttpClient */
    protected $httpClient;

    /** @var  string */
    protected $defaultClient = 'Webhook-Client';

    public function __construct()
    {
        $this->httpClient = HttpClientDiscovery::find();
    }

    /**
     * @param Message $message
     *
     * @return RequestResult
     */
    public function handle(Message $message)
    {
        $request = $this->createRequest($message);

        $result = RequestResult::success();

        try {
            /** @var ResponseInterface $response */
            $response = $this->httpClient->sendRequest($request);

            if ($response->getStatusCode() !== $message->getExpectedCode()) {
                return RequestResult::codeMissMatch();
            }

            if ($message->getExpectedContent() !== null
                && strpos($response->getBody(), $message->getExpectedContent()) === false
            ) {
                return RequestResult::contentMissMatch();
            }

        } catch (HttpException $e) {
            $result = RequestResult::transportError();
        }

        return $result;
    }

    /**
     * @param Message $message
     *
     * @return Request
     */
    private function createRequest(Message $message)
    {
        $headers = [
//            'Next-Retry'  => $message->getNextAttempt()->format('U'),
            'Retry-Count' => $message->getAttempts(),
            'User-Agent'  => $message->getUserAgent() ? $message->getUserAgent() : $this->defaultClient
        ];

        if ($message->isRaw()) {
            $body = $message->getBody();
        } else {
            $body = http_build_query($message->getBody());
        }

        return new Request('POST', $message->getUrl(), $headers, $body);
    }
}