<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/

namespace App\Event;

use App\Service\AuthService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Psr\Log\LoggerInterface;

readonly class RequestHeaderSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['captureHeaders', 1000], // Alta prioridade para executar cedo
            ],
        ];
    }

    public function captureHeaders(RequestEvent $event): void
    {
        // Só processa se for a request principal
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();
        $headers = $request->headers->all();
//        if($request->getPathInfo() == 'api/ipd/auth') {
//            AuthService::$agent = (int) $request->headers->get('X-Client-Agent');
//            AuthService::$fingerprint = (string) $request->headers->get('X-Fingerprint');
//        }
        // Log dos headers (você pode adaptar para fazer o que precisar)
//        $this->logger->info('Request Headers Captured', [
//            'method' => $request->getMethod(),
//            'uri' => $request->getUri(),
//            'headers' => $headers,
//            'user_agent' => $request->headers->get('User-Agent'),
//            'content_type' => $request->headers->get('Content-Type'),
//            'authorization' => $request->headers->get('Authorization'),
//        ]);

//        // Exemplo: Adicionar headers à request para uso posterior
//        $request->attributes->set('_captured_headers', $headers);

        // Exemplo: Validar header específico
//        if (!$request->headers->has('X-API-Key')) {
//            // Você pode lançar uma exceção ou apenas logar
//            $this->logger->warning('Missing X-API-Key header');
//        }

        // Exemplo: Manipular headers
//        if (
//            !$agent = $request->headers->get('X-Client-Agent') ||
//                !$application = $request->headers->get('X-Client-Application') ||
//                    !$fingerprint = $request->headers->get('X-Fingerprint')) {
//            throw new \Exception(
//                "Missing 'X-Client-Agent' or 'X-Client-Application' or 'X-Fingerprint' headers."
//            );
//        }
        AuthService::$agent = (int) $request->headers->get('X-Client-Agent');
        AuthService::$application = (int) $request->headers->get('X-Client-application');
        AuthService::$fingerprint = (string) $request->headers->get('X-Fingerprint');
    }
}
