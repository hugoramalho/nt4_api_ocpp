<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/

namespace App\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

//class ExceptionEventSubscriber implements EventSubscriberInterface
//{
//    public static function getSubscribedEvents(): array
//    {
//        return [
//            KernelEvents::EXCEPTION => 'onKernelException',
//        ];
//    }
//
//    public function onKernelException(ExceptionEvent $event): void
//    {
//        // Obtém a exceção
//        $exception = $event->getThrowable();
//        if ($_ENV['APP_ENV'] == 'prod' || $_ENV['APP_ENV'] == 'dev') {
//            $response = new JsonResponse();
//            // Se for uma exceção HTTP, definimos o status code e os headers apropriados
//            if ($exception instanceof HttpExceptionInterface) {
//                $response->setStatusCode($exception->getStatusCode());
////                $response->headers->replace($exception->getHeaders());
//                $message = $exception->getMessage();
//            } elseif ($exception->getCode() == 1) {
//                //Se o codigo for 1, eh uma business logic exception, e sua mensagem deve ser enviada ao usuario
//                $message = $exception->getMessage();
//                $response->setStatusCode(400);
//            } else {
//                //Caso nao seja, eh enviado o codigo do erro para o usuario (util para investigacao de log)
////                $message = 'Fail to process request';
//                $message = $exception->getMessage();
////                $this->auditor->error('Fail to process request: '.$exception->getMessage());
//                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
//            }
//            $response->setData(
//                [
//                    'success' => false,
//                    'message' => $message,
//                    'code' => $exception->getCode()
//                ]);
//            $event->setResponse($response);
//        }
//    }
//}


/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/

//namespace App\Event;
//
//use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Event\ExceptionEvent;
//use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
//use Symfony\Component\HttpKernel\KernelEvents;
//use Symfony\Component\Validator\Exception\ValidationFailedException;

readonly class ExceptionEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
//        $exception = $event->getThrowable();
//        dd($exception);
//        if ($_ENV['APP_ENV'] == 'prod' || $_ENV['APP_ENV'] == 'dev') {
////            dd($exception);
//            $response = new JsonResponse();
//            // --- Tratamento especial para falhas de Constraint ---
//            if ($exception instanceof ValidationFailedException) {
//                $violations = $exception->getViolations();
//                $errors = [];
//
//                foreach ($violations as $violation) {
//                    $errors[] = sprintf(
//                        "%s: %s",
//                        $violation->getPropertyPath(),
//                        $violation->getMessage()
//                    );
//                }
//
//                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
//                $response->setData([
//                    'success' => false,
//                    'message' => 'Constraint validation exception',
//                    'code' => 0,
//                    'errors' => $errors,
//                ]);
//
//                $event->setResponse($response);
//                return;
//            }
//
//            // Se for uma exceção HTTP
//            if ($exception instanceof HttpExceptionInterface) {
//                $response->setStatusCode($exception->getStatusCode());
//                $message = $exception->getMessage();
//            } elseif ($exception->getCode() == 1) {
//                // Exceção de regra de negócio
//                $message = $exception->getMessage();
//                $response->setStatusCode(400);
//            } else {
//                // Demais exceções internas
//                $message = $exception->getMessage();
//                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
//            }
//
//            $response->setData([
//                'success' => false,
//                'message' => $message,
//                'code' => $exception->getCode(),
//            ]);
//
//            $event->setResponse($response);
//        }
    }
}
