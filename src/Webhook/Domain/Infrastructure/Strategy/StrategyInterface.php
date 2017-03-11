<?php


namespace Webhook\Domain\Infrastructure\Strategy;

/**
 * Interface StrategyInterface.
 */
interface StrategyInterface
{
    /**
     *
     * @param int $attempt
     *
     * @return int
     */
    public function process(int $attempt): int;
}