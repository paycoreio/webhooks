<?php
declare(strict_types=1);


namespace Webhook\Bundle\Repository;


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
     * @return null|object|Webhook
     */
    public function get($id)
    {
        return $this->em->find(Webhook::class, $id);
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