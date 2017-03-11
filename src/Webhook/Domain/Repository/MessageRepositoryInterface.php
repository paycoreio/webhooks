<?php


namespace Webhook\Domain\Repository;


use Webhook\Domain\Model\Message;

interface MessageRepositoryInterface
{
    /**
     * @param $id
     *
     * @return Message|null
     */
    public function get($id);

    /**
     * @param Message $message
     *
     * @return void
     */
    public function save(Message $message);

    /**
     * @param Message $message
     *
     * @return void
     */
    public function update(Message $message);

    /**
     * @param \DateTime $time
     *
     * @return void
     */
    public function clearOutdated(\DateTime $time);
}