<?php


namespace Webhook\Domain\Infrastructure\Strategy;


/**
 * Class AbstractStrategy
 * @package Webhook\Domain\Infrastructure\Strategy
 */
abstract class AbstractStrategy implements \Serializable, StrategyInterface
{
    const ALIAS = '';

    /**
     * @return string
     */
    public function serialize()
    {
        return json_encode(get_object_vars($this));
    }

    /**
     * @param string $serialized
     */
    public function unSerialize($serialized)
    {
        $data = json_decode($serialized);

        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }
    }

    /**
     * @return array
     */
    abstract public function getOptions(): array;
}