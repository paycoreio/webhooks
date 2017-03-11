<?php


namespace Tests\Strategy;


use PHPUnit\Framework\TestCase;
use Webhook\Domain\Infrastructure\Strategy\LinearStrategy;

class LinearStrategyTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_fail_with_wrong_params()
    {
        new LinearStrategy(-1);
    }

    /**
     * @test
     */
    public function it_should_add_constant_intervals()
    {
        $int = 3;
        $strategy = new LinearStrategy($int);

        for ($i = 1; $i < 10; $i++) {
            self::assertEquals($int * $i, $strategy->process($i));
            $i++;
        }
    }
}