<?php


namespace Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Webhook\Domain\Model\Message;
use Webhook\Infrastructure\Handler;


class HandlerTest extends TestCase
{
    /**
     * @var Handler
     */
    protected $handler;

    public function setUp()
    {
        $this->handler = new Handler();
//        putenv('HTTP_PROXY=http://localhost:3128');
    }

    /**
     * @Tests
     */
    public function testHandlerIsHandler()
    {
        Assert::assertInstanceOf(Handler::class, $this->handler);
    }

    /**
     * @dataProvider httpDataProvider
     *
     * @param $url
     * @param $expectedCode
     * @param $expectedContent
     * @param $content
     * @param $expected
     */
    public function testRequest($url, $expectedCode, $expectedContent, $content, $expected)
    {
        $message = new Message($url, $content);
        $message->setExpectedCode($expectedCode);
        $message->setExpectedContent($expectedContent);

        $result = $this->handler->handle($message);

        Assert::assertEquals($expected, $result->isSuccess());
    }

    /**
     * @return array
     */
    public function httpDataProvider()
    {
        return [
            ['http://httpbin.org/status/500', 200, null, 'test', false],
            ['http://httpbin.org/status/200', 200, null, 'test', true],
            ['http://httpbin.org/post', 200, 'test', 'test', true],
            ['http://httpbin.org/post', 200, 'qwe', 'qwe', true],
            ['http://httpbin.org/post', 200, 'qwe', '{"test":"test"}', false],
            ['http://httpbin.org/post', 200, 'test', '{"test":"test"}', true],
            ['http://httpbin.org/post', 200, 'Webhook-Client', '{"test":"test"}', true],
            ['http://httpbin.org/status/301', 200, null, null, false],
            ['http://httpbin.org/status/302', 200, null, null, false],
        ];
    }
}
