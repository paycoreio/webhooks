<?php


namespace Webhook\Bundle\Repository;


use Doctrine\ORM\EntityManager;
use Webhook\Domain\Model\Message;
use Webhook\Domain\Repository\MessageRepositoryInterface;

/**
 * Class MessageRepository
 *
 * @package Webhook\Bundle\Repository
 */
class MessageRepository implements MessageRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * MessageRepository constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     *
     * @return null|object|Message
     */
    public function get($id)
    {
        return $this->em->find(Message::class, $id);
    }

    /**
     * @param Message $message
     */
    public function update(Message $message)
    {
        $this->save($message);
    }

    /**
     * @param Message $message
     */
    public function save(Message $message)
    {
        $this->em->persist($message);
        $this->em->flush();
    }

    /**
     * @param \DateTime $time
     */
    public function clearOutdated(\DateTime $time)
    {
        $this->em->createQuery('DELETE FROM Webhook\Domain\Model\Message m where m.created  < :time')
            ->setParameter('time', $time->format(\DATE_RFC822))->execute();
    }
}