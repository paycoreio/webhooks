<?php
declare(strict_types=1);


namespace Webhook\Domain\Infrastructure\Strategy;

/**
 * Interface SetOptionsInterface
 *
 * @package Webhook\Domain\Infrastructure\Strategy
 */
interface SetOptionsInterface
{
    public function setOptions(array $options);
}