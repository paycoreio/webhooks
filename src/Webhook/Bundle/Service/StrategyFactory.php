<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service;


use Webhook\Bundle\Builder\ExponentialStrategyBuilder;
use Webhook\Bundle\Builder\LinearStrategyBuilder;
use Webhook\Bundle\Controller\ParameterBag\Dto\AbstractStrategyParameterDto;
use Webhook\Bundle\Controller\ParameterBag\Dto\ExponentialStrategyParametersDto;
use Webhook\Bundle\Controller\ParameterBag\Dto\LinearStrategyParametersDto;
use Webhook\Domain\Infrastructure\Strategy\ExponentialStrategy;
use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;

/**
 * Class StrategyFactory
 * @package Webhook\Bundle\Service
 */
final class StrategyFactory
{
    /**
     * @param AbstractStrategyParameterDto $dto
     * @return ExponentialStrategy|LinearStrategy
     */
    public function createStrategy(AbstractStrategyParameterDto $dto = null)
    {
        $strategy = new LinearStrategy();
        if ($dto instanceof LinearStrategyParametersDto) {
            $strategy = $this->createLinearStrategy($dto);
        } else if ($dto instanceof ExponentialStrategyParametersDto) {
            $strategy = $this->createExponentialStrategy($dto);
        }
        return $strategy;
    }

    /**
     * @param LinearStrategyParametersDto $dto
     * @return object|LinearStrategy
     */
    private function createLinearStrategy(LinearStrategyParametersDto $dto)
    {
        $builder = new LinearStrategyBuilder();
        if ($dto->interval !== null) {
            $builder->interval = $dto->interval;
        }
        if ($dto->multiplier !== null) {
            $builder->multiplier = $dto->multiplier;
        }
        return $builder->buildStrategy();
    }

    /**
     * @param ExponentialStrategyParametersDto $dto
     * @return object|LinearStrategy
     */
    public function createExponentialStrategy(ExponentialStrategyParametersDto $dto)
    {
        $builder = new ExponentialStrategyBuilder();
        if ($dto->interval !== null) {
            $builder->interval = $dto->interval;
        }
        if ($dto->base !== null) {
            $builder->base = $dto->base;
        }
        return $builder->buildStrategy();
    }

    /**
     * @return array
     */
    public static function getAvailableStrategies()
    {
        return [
            ExponentialStrategy::ALIAS,
            LinearStrategy::ALIAS,
        ];
    }
}