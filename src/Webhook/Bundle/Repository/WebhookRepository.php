<?php
declare(strict_types=1);


namespace Webhook\Bundle\Repository;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Webhook\Domain\Model\Webhook;
use Webhook\Domain\Repository\WebhookRepositoryInterface;

/**
 * Class MessageRepository
 *
 * @package Webhook\Bundle\Repository
 */
class WebhookRepository implements WebhookRepositoryInterface
{
    /**
     * @var ObjectManager
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
     * @return null|object|Webhook
     */
    public function get($id)
    {
        return $this->em->find(Webhook::class, $id);
    }

    /**
     * @param $reference
     *
     * @return null|object|Webhook
     */
    public function getByReference($reference)
    {
        $qb = $this->em->getRepository(Webhook::class)->createQueryBuilder('e');

        $qb
            ->where('e.reference=:reference')
            ->setParameter('reference', $reference);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $count
     *
     * @return Webhook[]
     */
    public function getLastWebhooks(int $count): array
    {
        $qb = $this->em->getRepository(Webhook::class)->createQueryBuilder('e');
        $qb->orderBy('e.created', 'DESC');
        $qb->setMaxResults($count);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Webhook $webhook
     */
    public function update(Webhook $webhook)
    {
        $this->save($webhook);
    }

    /**
     * @param Webhook $webhook
     */
    public function save(Webhook $webhook)
    {
        $this->em->persist($webhook);
        $this->em->flush();
    }

    /**
     * @param \DateTime $time
     */
    public function clearOutdated(\DateTime $time)
    {
        $this->em->createQuery('DELETE FROM Webhook\Domain\Model\Webhook m where m.created  < :time')
            ->setParameter('time', $time->format(\DATE_RFC822))->execute();
    }
}