<?php
declare(strict_types=1);


namespace Webhook\Bundle\Controller\ParameterBag\Dto;

/**
 * Class ExponentialStrategyParametersDto
 * @package Webhook\Bundle\Controller\ParameterBag\Dto
 */
class ExponentialStrategyParametersDto extends AbstractStrategyParameterDto
{
    /** @var float */
    public $base;
}