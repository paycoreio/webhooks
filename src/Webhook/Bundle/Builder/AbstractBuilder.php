<?php
declare(strict_types=1);


namespace Webhook\Bundle\Builder;

/**
 * Class AbstractBuilder
 * @package Webhook\Bundle\Builder
 */
abstract class AbstractBuilder
{
    /**
     * A placeholder for constructor arguments.
     *
     * @var array
     */
    protected $builderPlaceholderData;

    /**
     * AbstractBuilder constructor.
     */
    public function __construct()
    {
        $this->builderPlaceholderData = $this->configureParameters();
        if (0 === count($this->builderPlaceholderData)) {
            throw new \RuntimeException('Builder expects at least one parameter to be defined.');
        }
    }

    /**
     * Builds new building class from provided arguments.
     *
     * @return object
     */
    public function build()
    {
        $reflector = new \ReflectionClass($this->getObjectFqcn());
        return $reflector->newInstanceArgs(array_values($this->builderPlaceholderData));
    }

    /**
     * Set building class constructor arguments from array.
     *
     * @param array $values Values for constructor arguments of building class.
     * @return AbstractBuilder $this Fluent interface
     */
    public function fromArray(array $values)
    {
        foreach ($values as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * Get all building class constructor arguments as array.
     *
     * @return array
     */
    public function toArray(array $keys = array())
    {
        if (0 === count($keys)) {
            return $this->builderPlaceholderData;
        }
        return array_intersect_key($this->builderPlaceholderData, array_flip($keys));
    }

    /**
     * Set building class constructor argument.
     *
     * @param string $name Argument name.
     * @param mixed $value Argument value.
     */
    public function __set($name, $value)
    {
        if (!array_key_exists($name, $this->builderPlaceholderData)) {
            throw new \InvalidArgumentException(sprintf('Unknown property "%s" in "%s".', $name, get_class($this)));
        }
        $this->builderPlaceholderData[$name] = $value;
    }

    /**
     * Get building class constructor argument.
     *
     * @param string $name Argument name.
     * @return mixed Argument value.
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->builderPlaceholderData)) {
            throw new \InvalidArgumentException(sprintf('Unknown property "%s" in "%s".', $name, get_class($this)));
        }
        return $this->builderPlaceholderData[$name];
    }

    /**
     * Check if building class constructor argument is defined.
     *
     * @param string $name Argument name.
     * @return bool TRUE if argument is defined.
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->builderPlaceholderData);
    }

    /**
     * Get/set building class constructor argument.
     *
     * @param string $name A method name.
     * @param array $arguments A method arguments.
     * @return $this|mixed Fluent interface or argument value, depending on method name.
     */
    public function __call($name, array $arguments)
    {
        $property = lcfirst(substr($name, 3));
        if (
            !array_key_exists($property, $this->builderPlaceholderData)
            ||
            (strpos($name, 'set') !== 0 && strpos($name, 'get') !== 0)
        ) {
            throw new \BadMethodCallException(sprintf('Unknown method "%s" in "%s".', $name, get_class($this)));
        }
        if (count($arguments) !== 1 && strpos($name, 'set') === 0) {
            throw new \BadMethodCallException(sprintf('Method "%s" in "%s" expects exactly one parameter.', $name, get_class($this)));
        }
        if (count($arguments) !== 0 && strpos($name, 'get') === 0) {
            throw new \BadMethodCallException(sprintf('Method "%s" in "%s" does not use any parameter.', $name, get_class($this)));
        }
        if (strpos($name, 'get') === 0) {
            return $this->builderPlaceholderData[$property];
        }
        $this->builderPlaceholderData[$property] = $arguments[0];
        return $this;
    }

    /**
     * Function call to builder object instance will produce building class.
     *
     * @return object
     */
    public function __invoke()
    {
        return $this->build();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->builderPlaceholderData);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->builderPlaceholderData[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->offsetExists($offset)) {
            throw new \RuntimeException(sprintf('Undefined property "%s" provided.', $offset));
        }
        $this->builderPlaceholderData[$offset] = $value;
    }

    /**
     * Unused, throws an exception.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('It is not allowed to unset builder property.');
    }

    /**
     * Produces new builder.
     *
     * @return static
     */
    public static function createBuilder()
    {
        return new static();
    }

    /**
     * Configure builder parameters that will be passed to building class constructor.
     *
     * @return array
     */
    abstract protected function configureParameters();

    /**
     * Get full qualified class name of class which instance ought to be constructed.
     *
     * @return string
     */
    abstract protected function getObjectFqcn();
}