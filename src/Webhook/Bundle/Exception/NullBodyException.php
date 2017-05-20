<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;


/**
 * Class NullBodyException
 * @package Webhook\Bundle\Exception
 */
class NullBodyException extends \InvalidArgumentException
{
    /**
     * NullBodyException constructor.
     * @param string $message
     */
    public function __construct($message = 'Null message body')
    {
        parent::__construct($message);
    }
}