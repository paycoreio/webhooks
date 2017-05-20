<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;

/**
 * Class NullURLException
 * @package Webhook\Bundle\Exception
 */
class NullURLException extends \InvalidArgumentException
{
    /**
     * NullURLException constructor.
     * @param string $message
     */
    public function __construct($message = 'Message URL is null')
    {
        parent::__construct($message);
    }
}