<?php


namespace Webhook\Bundle\Repository;


use Doctrine\ORM\EntityManager;
use Webhook\Domain\Model\Message;
use Webhook\Domain\Repository\MessageRepositoryInterface;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function get($id)
    {
        return $this->em->find(Message::class, $id);
    }

    public function update(Message $message)
    {
        $this->save($message);
    }

    public function save(Message $message)
    {
        $this->em->persist($message);
        $this->em->flush();
    }

    public function clearOutdated(\DateTime $time)
    {
        $this->em->createQuery('DELETE FROM Webhook\Domain\Model\Message m where m.created  < :time')
            ->setParameter('time', $time->format(\DATE_RFC822))->execute();
    }
}