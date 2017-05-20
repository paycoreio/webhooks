<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;

/**
 * Class BodyMissingException
 * @package Webhook\Bundle\Exception
 */
class BodyMissingException extends \InvalidArgumentException
{
    /**
     * BodyMissingException constructor.
     * @param string $message
     */
    public function __construct($message = 'Message body is missed')
    {
        parent::__construct($message);
    }
}