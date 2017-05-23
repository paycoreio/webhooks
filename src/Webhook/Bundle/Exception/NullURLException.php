<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;

/**
 * Class NullURLException
 *
 * @package Webhook\Bundle\Exception
 */
class NullURLException extends \InvalidArgumentException
{
    /**
     * NullURLException constructor.
     */
    public function __construct()
    {
        parent::__construct('Message URL is null');
    }
}