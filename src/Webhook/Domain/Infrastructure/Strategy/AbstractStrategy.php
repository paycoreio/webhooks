<?php


namespace Webhook\Domain\Infrastructure\Strategy;


/**
 * Class AbstractStrategy
 *
 * @package Webhook\Domain\Infrastructure\Strategy
 */
abstract class AbstractStrategy implements \Serializable, StrategyInterface
{
    /**
     * @return string
     */
    public function serialize()
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
        $data = json_decode($serialized);

        $this->setOptions($data);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $val) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->{$method}($val);
            }
        }
    }
}