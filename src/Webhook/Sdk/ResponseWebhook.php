<?php
declare(strict_types=1);


namespace Webhook\Sdk;

/**
 * Class ResponseMessage
 *
 * @package Webhook\Sdk
 */
class ResponseWebhook
{
    /** @var string */
    public $id;

    /** @var string */
    public $url;

    /** @var string */
    public $status;

    /** @var array|string */
    public $body;

    /** @var bool */
    public $raw;

    /** @var string */
    public $created;

    /** @var string */
    public $processed;

    /** @var string */
    public $nextAttempt;

    /** @var int */
    public $attempt;

    /** @var int */
    public $maxAttempts;

    /** @var int */
    public $expectedCode;

    /** @var string */
    public $expectedContent;

    /** @var string */
    public $userAgent;

    /** @var string */
    public $statusDetails;

    /** @var array */
    public $strategyOptions;

    /**
     * ResponseMessage constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            foreach ($options as $k => $v) {
                if (property_exists($this, $k)) {
                    if (static::isTimestamp($v)) {
                        $v = (new \DateTime())->setTimestamp((int) $v);
                    }
                    $this->{$k} = $v;
                }
            }
        }
    }

    public static function isTimestamp($timestamp)
    {
        return ((string) (int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX)
            && (!strtotime($timestamp));
    }

    /**
     * @param $json
     *
     * @return static
     */
    public static function fromJson($json)
    {
        $array = json_decode($json, true);

        return new static($array);
    }
}