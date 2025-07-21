<?php

declare(strict_types=1);

namespace App\Application\Listeners;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    private RequestStack  $requestStack;

    private LoggerInterface $logger;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): ?Response
    {
        $exception = $event->getThrowable();
        $status    = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
            'path'      => $this->requestStack->getCurrentRequest()?->getPathInfo(),
        ]);

        $payload = [
            'data'  => (object)[],
            'error' => [
                'code'    => (string) $status,
                'message' => $exception->getMessage(),
            ],
        ];

        $response = new JsonResponse($payload, $status);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
