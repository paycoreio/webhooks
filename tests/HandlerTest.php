<?php


namespace Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Webhook\Domain\Infrastructure\Handler;
use Webhook\Domain\Model\Message;

/**
 * Class HandlerTest
 * @package Tests
 */
class HandlerTest extends TestCase
{
    /**
     * @var Handler
     */
    protected $handler;

    public function setUp()
    {
        $this->handler = new Handler();
    }

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

        self::assertEquals($expected, $result->isSuccess());
    }

    /**
     * @param $message
     *
     * @dataProvider dataProvider
     */
    public function testTransportError($message)
    {
        $result = $this->handler->handle($message);

        self::assertTrue($result->isTransportError());
    }

    public function dataProvider()
    {
        return [
            [new Message('https://httpbinorg/foo', '')]
        ];
    }

    public function testRawData()
    {
        $message = new Message('http://httpbin.org/post', ['foo' => 'bar']);
        $message->asJson();

        $res = $this->handler->handle($message);
        $this->assertTrue($res->isSuccess());
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
