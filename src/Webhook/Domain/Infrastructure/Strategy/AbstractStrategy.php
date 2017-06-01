<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure\Strategy;


/**
 * Class AbstractStrategy
 *
 * @package Webhook\Domain\Infrastructure\Strategy
 */
abstract class AbstractStrategy implements \Serializable, \JsonSerializable, StrategyInterface
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getOptions() + ['name' => StrategyRegistry::getName($this)];
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return json_encode($this->getOptions());
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param string $serialized
     */
    public function unSerialize($serialized)
    {
        $data = json_decode($serialized, true);

        $this->setOptions($data);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $val) {
            $method = 'set' . ucfirst($key);
            if ($method !== __FUNCTION__ && method_exists($this, $method)) {
                $this->{$method}($val);
            }
        }
    }
}