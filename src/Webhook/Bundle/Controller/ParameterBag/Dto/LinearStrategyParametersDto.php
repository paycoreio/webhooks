<?php
declare(strict_types=1);


namespace Webhook\Bundle\Controller\ParameterBag\Dto;

/**
 * Class LinearStrategyParametersDto
 * @package Webhook\Bundle\Controller\ParameterBag\StrategiesParameters
 */
class LinearStrategyParametersDto extends AbstractStrategyParameterDto
{
    /** @var int */
    public $multiplier;
}