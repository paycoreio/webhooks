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
     */
    public function __construct()
    {
        parent::__construct('Message body is missed');
    }
}