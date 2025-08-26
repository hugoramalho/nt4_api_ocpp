<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 21/08/2025
 **/

namespace App\Event;

use App\Entity\AuthToken;
use App\Entity\User;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class JWTSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_DECODED => ['onJWTDecoded']
        ];
    }

    /**
     * @throws \Exception
     */
    public function onJwtDecoded(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();
        if (!$event->isValid() || !$payload['fp']) {
            throw new \Exception('Invalid JWT.');
        }
        AuthService::$fingerprint = (string) $payload['fp'];
        AuthService::$agent = (int) $payload['aud'];
        AuthService::$application = (int) $payload['iss'];
        AuthService::$accountUuid = $payload['cid'];
        AuthService::$userUuid = $payload['uid'];
    }

}
