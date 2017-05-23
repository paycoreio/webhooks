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
     */
    public function __construct()
    {
        parent::__construct('Message URL is missed');
    }
}