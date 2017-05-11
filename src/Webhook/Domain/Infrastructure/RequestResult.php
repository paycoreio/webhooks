<?php


namespace Webhook\Domain\Infrastructure;

final class RequestResult
{
    const SUCCESS = 'success';
    const CODE_MISS_MATCH = 'code_miss_match';
    const CONTENT_MISS_MATCH = 'content_miss_match';
    const TRANSPORT_ERROR = 'transport_error';

    /** @var string */
    private $status;

    /** @var  string|null */
    private $details;

    private function __construct(string $status, string $details = '')
    {
        $this->status = $status;
        $this->details = $details;
    }

    public static function success()
    {
        return new self(self::SUCCESS);
    }

    public static function codeMissMatch($code)
    {
        return new self(self::CODE_MISS_MATCH, (string) $code);
    }

    public static function contentMissMatch()
    {
        return new self(self::CONTENT_MISS_MATCH);
    }

    public static function transportError(string $error)
    {
        return new self(self::TRANSPORT_ERROR, $error);
    }

    /**
     * @return bool
     */
    public function isTransportError(): bool
    {
        return $this->status === self::TRANSPORT_ERROR;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status === self::SUCCESS;
    }

    /**
     * @return bool
     */
    public function isContentMissMatch(): bool
    {
        return $this->status === self::CONTENT_MISS_MATCH;
    }

    /**
     * @return bool
     */
    public function isCodeMissMatch(): bool
    {
        return $this->status === self::CODE_MISS_MATCH;
    }

    /**
     * @return null|string
     */
    public function getDetails()
    {
        return $this->status . ': ' . $this->details;
    }
}