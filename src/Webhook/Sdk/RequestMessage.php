<?php
declare(strict_types=1);


namespace Webhook\Sdk;

use Webhook\Sdk\Enum\StrategiesEnum;

/**
 * Class RequestMessage
 * @package Webhook\Sdk
 */
class RequestMessage implements \JsonSerializable
{
    /** @var string */
    private $url;

    /** @var string */
    private $body;

    /** @var array */
    private $query;

    /**
     * Message constructor.
     * @param string $url
     * @param string $body
     */
    public function __construct(string $url, string $body)
    {
        $this->url = $url;
        $this->body = $body;
        $this->query = [];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @param string $strategy
     */
    public function setStrategy(string $strategy)
    {
        $this->query['strategy'] = $strategy;
    }

    /**
     * @param int $interval
     */
    public function setInterval(int $interval)
    {
        $this->checkIfStrategyExists();
        $this->query['interval'] = $interval;
    }

    /**
     * @param int $multiplier
     */
    public function setMultiplier(int $multiplier)
    {
        $this->checkIfStrategyIsLinear();
        $this->query['multiplier'] = $multiplier;
    }

    /**
     * @param float $base
     */
    public function setBase(float $base)
    {
        $this->checkIfStrategyIsExponential();
        $this->query['base'] = $base;
    }

    private function checkIfStrategyIsLinear()
    {
        $this->checkIfStrategyExists();
        if ($this->query['strategy'] !== StrategiesEnum::LINEAR) {
            throw new \InvalidArgumentException('You can set parameter only for linear strategy');
        }
    }

    private function checkIfStrategyIsExponential()
    {
        $this->checkIfStrategyExists();
        if ($this->query['strategy'] !== StrategiesEnum::EXPONENTIAL) {
            throw new \InvalidArgumentException('You can set parameter only for exponential strategy');
        }
    }

    private function checkIfStrategyExists()
    {
        if (!isset($this->query['strategy'])) {
            throw new \RuntimeException('Before set any parameter, please provide strategy to the query');
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'url' => $this->url,
            'body' => $this->body,
        ];
    }
}