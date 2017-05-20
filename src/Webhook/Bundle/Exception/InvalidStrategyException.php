<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;


/**
 * Class InvalidStrategyException
 * @package Webhook\Bundle\Exception
 */
class InvalidStrategyException extends \InvalidArgumentException
{
    /**
     * InvalidStrategyException constructor.
     * @param string $strategy
     * @param string $message
     */
    public function __construct(string $strategy, string $message = 'Invalid strategy passed, %s given')
    {
        parent::__construct(sprintf($message, $strategy));
    }
}