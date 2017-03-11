<?php


namespace Webhook\Domain\Infrastructure\Strategy;


abstract class AbstractStrategy implements \Serializable, StrategyInterface
{
    public function serialize()
    {
        return json_encode(get_object_vars($this));
    }

    public function unSerialize($serialized)
    {
        $data = json_decode($serialized);

        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }
    }
}