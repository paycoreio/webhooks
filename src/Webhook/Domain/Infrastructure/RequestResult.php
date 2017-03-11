<?php


namespace Webhook\Domain\Infrastructure;

final class RequestResult
{
    const SUCCESS = 'success';
    const CODE_MISS_MATCH = 'code_miss_match';
    const CONTENT_MISS_MATCH = 'content_miss_match';
    const TRANSPORT_ERROR = 'transport_error';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function success()
    {
        return new self(self::SUCCESS);
    }

    public static function codeMissMatch()
    {
        return new self(self::CODE_MISS_MATCH);
    }

    public static function contentMissMatch()
    {
        return new self(self::CONTENT_MISS_MATCH);
    }

    public static function transportError()
    {
        return new self(self::TRANSPORT_ERROR);
    }

    /**
     * @return bool
     */
    public function isTransportError()
    {
        return $this->status === self::TRANSPORT_ERROR;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status === self::SUCCESS;
    }

    /**
     * @return bool
     */
    public function isContentMissMatch()
    {
        return $this->status === self::CONTENT_MISS_MATCH;
    }

    /**
     * @return bool
     */
    public function isCodeMissMatch()
    {
        return $this->status === self::CODE_MISS_MATCH;
    }
}