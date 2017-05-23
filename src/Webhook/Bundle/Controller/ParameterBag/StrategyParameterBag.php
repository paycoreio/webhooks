<?php
declare(strict_types=1);


namespace Webhook\Bundle\Controller\ParameterBag;

use Symfony\Component\HttpFoundation\ParameterBag;
use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;

/**
 * Class StrategyParameterBag
 * @package Webhook\Bundle\Controller\ParameterBag
 */
class StrategyParameterBag
{
    private const ALIAS_KEY = 'strategy';

    /** @var string */
    private $strategyAlias = LinearStrategy::ALIAS;

    /** @var array */
    private $options;

    /**
     * StrategyParameterBag constructor.
     * @param ParameterBag $bag
     */
    public function __construct(ParameterBag $bag)
    {
        if ($strategy = $bag->getAlpha(self::ALIAS_KEY)) {
            $this->strategyAlias = $strategy;
            $bag->remove(self::ALIAS_KEY);
        }
        $this->options = $bag->all();
    }

    /**
     * @return string
     */
    public function getStrategyAlias(): string
    {
        return $this->strategyAlias;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}