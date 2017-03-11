<?php


namespace Tests\Strategy;


use PHPUnit\Framework\TestCase;
use Webhook\Domain\Infrastructure\Strategy\ExponentialStrategy;

class ExponentialStrategyTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_fail_with_wrong_params()
    {
        new ExponentialStrategy(-1);
    }

    /**
     * @test
     */
    public function it_should_add_exponential_intervals()
    {
        $int = 2;
        $base = 3;

        $strategy = new ExponentialStrategy($int, $base);

        for ($i = 1; $i < 10; $i++) {
            self::assertEquals($int + pow($base, $i), $strategy->process($i));
            $i++;
        }
    }
}