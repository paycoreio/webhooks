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
    /**
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options);
}