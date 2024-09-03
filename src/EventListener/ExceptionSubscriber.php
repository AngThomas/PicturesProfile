<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private iterable $exceptionHandlers;

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
        $exceptionClass = basename(get_class($exception));
        foreach ($this->exceptionHandlers as $exceptionHandler) {
            if (in_array($exceptionClass, $exceptionHandler->getSupportedExceptions($exception))) {
                $response = $exceptionHandler->handle($event->getThrowable());
                $event->setResponse($response);

                return;
            }
        }
    }
}
