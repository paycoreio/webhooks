<?php
declare(strict_types=1);


namespace Webhook\Bundle\Controller\ParameterBag;

use Symfony\Component\HttpFoundation\ParameterBag;
use Webhook\Bundle\Controller\ParameterBag\Dto\AbstractStrategyParameterDto;
use Webhook\Bundle\Controller\ParameterBag\Dto\ExponentialStrategyParametersDto;
use Webhook\Bundle\Controller\ParameterBag\Dto\LinearStrategyParametersDto;
use Webhook\Bundle\Exception\InvalidStrategyException;
use Webhook\Bundle\Service\StrategyFactory;
use Webhook\Domain\Infrastructure\Strategy\ExponentialStrategy;
use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;
use Webmozart\Assert\Assert;

/**
 * Class MessageParameterBag
 * @package Webhook\Bundle\Controller\ParameterBag
 */
class StrategyParameterBag
{
    private const STRATEGY_KEY = 'strategy';
    private const INTERVAL_KEY = 'interval';
    private const BASE_KEY = 'base';
    private const MULTIPLIER_KEY = 'multiplier';
    /** @var AbstractStrategyParameterDto */
    private $strategyDto;

    /**
     * MessageParameterBag constructor.
     * @param ParameterBag $bag
     */
    public function __construct(ParameterBag $bag)
    {
        if ($strategyAlias = $bag->getAlpha(self::STRATEGY_KEY)) {
            if (!in_array($strategyAlias, StrategyFactory::getAvailableStrategies(), true)) {
                throw new InvalidStrategyException($strategyAlias);
            }
            $dto = null;
            if ($strategyAlias === LinearStrategy::ALIAS
                && $multiplier = $bag->getInt(self::MULTIPLIER_KEY, 1)
            ) {
                $dto = new LinearStrategyParametersDto();
                $dto->multiplier = $multiplier;
            }
            if ($strategyAlias === ExponentialStrategy::ALIAS
                && $base = (float)$bag->get(self::BASE_KEY, 2.0)
            ) {
                $dto = new ExponentialStrategyParametersDto();
                $dto->base = $base;
            }
            if ($dto !== null && $interval = $bag->getInt(self::INTERVAL_KEY, 5)) {
                $dto->interval = $interval;
            }
            $this->strategyDto = $dto;
        }
    }

    /**
     * @return null|AbstractStrategyParameterDto|ExponentialStrategyParametersDto|LinearStrategyParametersDto
     */
    public function getDto()
    {
        return $this->strategyDto;
    }
}