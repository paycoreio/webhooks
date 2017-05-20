<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;


/**
 * Class URLMissingException
 * @package Webhook\Bundle\Exception
 */
class URLMissingException extends \InvalidArgumentException
{
    /**
     * URLMissingException constructor.
     * @param string $message
     */
    public function __construct($message = 'Message URL is missed')
    {
        parent::__construct($message);
    }
}