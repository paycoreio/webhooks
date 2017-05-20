<?php
declare(strict_types=1);


namespace Webhook\Bundle\Builder;


use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;

/**
 * Class LinearStrategyBuilder
 * @package Webhook\Bundle\Builder
 */
class LinearStrategyBuilder extends AbstractBuilder
{
    /**
     * @return LinearStrategy|object
     */
    public function buildStrategy()
    {
        return $this->build();
    }

    /**
     * Configure builder parameters that will be passed to building class constructor.
     *
     * @return array
     */
    protected function configureParameters()
    {
        return [
            'interval' => 5,
            'multiplier' => 1,
        ];
    }

    /**
     * Get full qualified class name of class which instance ought to be constructed.
     *
     * @return string
     */
    protected function getObjectFqcn()
    {
        return LinearStrategy::class;
    }
}