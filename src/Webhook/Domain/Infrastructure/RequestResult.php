<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure;

/**
 * Class RequestResult
 *
 * @package Webhook\Domain\Infrastructure
 */
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

    /**
     * RequestResult constructor.
     *
     * @param string $status
     * @param string|null $details
     */
    private function __construct(string $status, string $details = null)
    {
        $this->status = $status;
        $this->details = $details;
    }

    /**
     * @return RequestResult
     */
    public static function success(): RequestResult
    {
        return new self(self::SUCCESS);
    }

    /**
     * @param $code
     *
     * @return RequestResult
     */
    public static function codeMissMatch($code): RequestResult
    {
        return new self(self::CODE_MISS_MATCH, (string) $code);
    }

    /**
     * @return RequestResult
     */
    public static function contentMissMatch(): RequestResult
    {
        return new self(self::CONTENT_MISS_MATCH);
    }

    /**
     * @param string $error
     *
     * @return RequestResult
     */
    public static function transportError(string $error): RequestResult
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