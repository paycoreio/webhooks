<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure\Strategy;


class StrategyFactory
{
    /**
     * @param string $name
     * @param array $options
     *
     * @return StrategyInterface
     */
    public static function create(string $name, array $options = []): StrategyInterface
    {
        if (false !== $class = StrategyRegistry::getClassByName($name)) {
            /** @var StrategyInterface $instance */
            $instance = new $class;
            if ($instance instanceof SetOptionsInterface) {
                $instance->setOptions($options);
            }

            return $instance;

        }

        throw new \RuntimeException('Unsupported strategy name.');
    }
}