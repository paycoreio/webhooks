<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;

/**
 * Class NotUrlException
 * @package Webhook\Bundle\Exception
 */
class NotURLException extends \InvalidArgumentException
{
    /**
     * NotUrlException constructor.
     * @param string $providedUrl
     * @param string $message
     */
    public function __construct(string $providedUrl, $message = 'Not URL was passed to message, % given')
    {
        parent::__construct(sprintf($message, $providedUrl));
    }
}