<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service;


use Webhook\Bundle\Exception\InvalidStrategyException;
use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;
use Webhook\Domain\Infrastructure\Strategy\SetOptionsInterface;
use Webhook\Domain\Infrastructure\Strategy\StrategyInterface;

/**
 * Class StrategiesMapper
 * @package Webhook\Bundle\Service
 */
final class StrategiesMapper
{
    /** @var array */
    private $map;

    /**
     * StrategiesMapper constructor.
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * @param string $alias
     * @return StrategyInterface|SetOptionsInterface
     */
    public function getStrategyInstance(string $alias = null)
    {
        $map = $this->map;
        $instance = null;
        if ($alias !== null) {
            if (array_key_exists($alias, $map)) {
                $class = $map[$alias];
                $instance = new $class();
            } else {
                throw new InvalidStrategyException($alias);
            }
        } else {
            $defaultAliasStrategy = $map[LinearStrategy::ALIAS];
            $instance = new $defaultAliasStrategy();
        }
        return $instance;
    }
}