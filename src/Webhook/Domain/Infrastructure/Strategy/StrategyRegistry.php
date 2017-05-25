<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure\Strategy;


final class StrategyRegistry
{
    private static $map = [
        'exponential' => ExponentialStrategy::class,
        'linear'      => LinearStrategy::class,
    ];

    public static function getClassByName(string $name)
    {
        if (!array_key_exists($name, self::$map)) {
            return false;
        }

        return self::$map[$name];
    }

    public static function getName(StrategyInterface $strategy)
    {
        $class = get_class($strategy);

        if (!in_array($class, self::$map)) {
            return false;
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