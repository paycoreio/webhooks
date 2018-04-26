<?php

declare(strict_types=1);

namespace Webhook\Bundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class TerminateEventListener implements EventSubscriberInterface
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * TerminateEventListener constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => 'handle',
        ];
    }

    /**
     * @param PostResponseEvent $event
     *
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function handle(PostResponseEvent $event): void
    {
        $this->entityManager->clear();
    }
}
