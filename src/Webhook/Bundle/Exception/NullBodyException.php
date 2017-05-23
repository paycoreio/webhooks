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
     */
    public function __construct()
    {
        parent::__construct('Null message body');
    }
}