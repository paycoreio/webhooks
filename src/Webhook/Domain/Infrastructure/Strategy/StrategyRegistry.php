<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure\Strategy;


/**
 * Class StrategyRegistry
 *
 * @package Webhook\Domain\Infrastructure\Strategy
 */
final class StrategyRegistry
{
    private static $map = [
        'exponential' => ExponentialStrategy::class,
        'linear'      => LinearStrategy::class,
    ];

    /**
     * @param string $name
     *
     * @return string
     */
    public static function getClassByName(string $name):string
    {
        if (!array_key_exists($name, self::$map)) {
            throw new \InvalidArgumentException('Unsupported strategy class.');
        }

        return self::$map[$name];
    }

    /**
     * @param StrategyInterface $strategy
     *
     * @return string
     */
    public static function getName(StrategyInterface $strategy): string
    {
        $class = get_class($strategy);

        if (!in_array($class, self::$map)) {
            throw new \InvalidArgumentException('Unsupported strategy.');
        }

        return array_flip(self::$map)[$class];
    }

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return self::$map;
    }

    /**
     * @param array $map
     */
    public static function setMap(array $map)
    {
        self::$map = $map;
    }

}