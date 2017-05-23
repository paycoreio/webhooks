<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;

/**
 * Class NotUrlException
 *
 * @package Webhook\Bundle\Exception
 */
class NotURLException extends \InvalidArgumentException
{
    /**
     * NotUrlException constructor.
     *
     * @param string $providedUrl
     */
    public function __construct(string $providedUrl)
    {
        parent::__construct(sprintf('Not URL was passed to message, % given', $providedUrl));
    }
}