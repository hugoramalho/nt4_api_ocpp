<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/

namespace App\Event;

use App\Entity\AuthToken;
use App\Entity\BaseEntity;
use Carbon\Carbon;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: BaseEntity::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: BaseEntity::class)]
readonly class DoctrineEntityListener
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(BaseEntity $entity, LifecycleEventArgs $args): void
    {
        $entity->createdAt = Carbon::now();
        if (!$entity instanceof UserInterface && !$entity instanceof AuthToken) {
            $entity->createdBy = $this->security->getUser()->getUserIdentifier();
        }
    }

    public function preUpdate(BaseEntity $entity, PreUpdateEventArgs $args): void
    {
        $entity->updatedAt = Carbon::now();
        if (!$entity instanceof AuthToken) {
            $entity->updatedBy = $this->security->getUser()->getUserIdentifier();
        }
    }

    public function onClear(): void
    {
        // poderia logar ou monitorar clear apos erro de banco
        // (ex.: captura indireta de exceções em flush)
    }
}
