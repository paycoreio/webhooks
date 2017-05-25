<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure;

use GuzzleHttp\Psr7\Request;
use Http\Client\Exception\TransferException;
use Http\Discovery\HttpClientDiscovery;
use Psr\Http\Message\ResponseInterface;
use Webhook\Domain\Model\Message;

final class Handler implements HandlerInterface
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
                return RequestResult::codeMissMatch($response->getStatusCode());
            }

            if ($message->getExpectedContent() !== null
                && strpos($response->getBody()->getContents(), $message->getExpectedContent()) === false
            ) {
                return RequestResult::contentMissMatch();
            }

        } catch (TransferException $e) {
            $result = RequestResult::transportError($e->getMessage());
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
            'Next-Retry'  => $message->getNextAttempt()->format('U'),
            'Retry-Count' => $message->getAttempt(),
            'User-Agent'  => $message->getUserAgent() ? $message->getUserAgent() : $this->defaultClient
        ];

        if ($message->isRaw()) { // send json
            $headers['Content-Type'] = 'application/json';
            $body = json_encode($message->getBody());
        } else {
            $body = http_build_query($message->getBody());
        }

        return new Request('POST', $message->getUrl(), $headers, $body);
    }
}