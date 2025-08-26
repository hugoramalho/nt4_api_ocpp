<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Event;


use App\Entity\User\Account;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;


final readonly class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security               $security,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        // Executa após autenticação do JWT e resolução do usuário
        return [
            Events::JWT_AUTHENTICATED => ['onJWTAuthenticated', -10],
        ];
    }

    public function onJWTAuthenticated(JWTAuthenticatedEvent $event): void
    {
//        /** @var User $user */
//        $user = $this->security->getUser();
//        if (!$user instanceof UserInterface) {
//            return;
//        }
//        if(!$userAccount = $this->entityManager->getRepository(Account::class)->findOneBy(['user' => $user])) {
//            throw new \Exception('Account for user ' . $user->uuid . ' not found');
//        }
//        $user->account = $userAccount;
    }
}

