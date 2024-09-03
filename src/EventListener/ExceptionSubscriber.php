<?php

namespace App\EventListener;

use App\Interface\ExceptionHandlerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private array $exceptionHandlers;
    public function __construct(iterable $exceptionHandlers)
    {
        $this->exceptionHandlers = $exceptionHandlers;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        foreach ($this->exceptionHandlers as $exceptionHandler) {
            if (in_array($exception, $exceptionHandler->getSupportedExceptions($exception))) {
                $response = $exceptionHandler->handle($event->getThrowable());
                $event->setResponse($response);
                return;
            }
        }
    }
}
