<?php
declare(strict_types=1);


namespace Webhook\Bundle\Exception;


/**
 * Class InvalidStrategyException
 *
 * @package Webhook\Bundle\Exception
 */
class InvalidStrategyException extends \InvalidArgumentException
{
    /**
     * InvalidStrategyException constructor.
     *
     * @param string $strategy
     */
    public function __construct(string $strategy)
    {
        parent::__construct(sprintf('Invalid strategy passed, %s given', $strategy));
    }
}