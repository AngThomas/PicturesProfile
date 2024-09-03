<?php

namespace App\Handler\Exception;

use App\DTO\JmsSerializable\ExceptionDTO;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class KernelExceptionHandler
{
    private SerializerInterface $serializer;

    public function __construct(
        SerializerInterface $serializer,
    ) {
        $this->serializer = $serializer;
    }

    abstract public function getSupportedExceptions(): array;

    abstract public function handle(\Throwable $exception): Response;

    protected function assembleResponse(ExceptionDTO $exceptionDTO): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($exceptionDTO, 'json', SerializationContext::create()),
            $exceptionDTO->getStatusCode()
        );
    }
}
