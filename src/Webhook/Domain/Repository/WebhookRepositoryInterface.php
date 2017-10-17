<?php
declare(strict_types=1);


namespace Webhook\Domain\Repository;


use Webhook\Domain\Model\Webhook;

/**
 * Interface WebhookRepositoryInterface
 *
 * @package Webhook\Domain\Repository
 */
interface WebhookRepositoryInterface
{
    /**
     * @param $id
     *
     * @return Webhook|null
     */
    public function get($id);

    /**
     * @param Webhook $webhook
     *
     * @return void
     */
    public function save(Webhook $webhook);

    /**
     * @param Webhook $webhook
     *
     * @return void
     */
    public function update(Webhook $webhook);

    /**
     * @param \DateTime $time
     *
     * @return void
     */
    public function clearOutdated(\DateTime $time);

    /**
     * @param int $count
     *
     * @return array
     */
    public function getLastWebhooks(int $count): array;
}