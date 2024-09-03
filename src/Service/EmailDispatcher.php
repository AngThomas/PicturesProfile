<?php

namespace App\Service;

use App\Model\Email\EmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailDispatcher
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param EmailMessage[] $emailMessages
     */
    public function dispatchMulti(array $emailMessages): void
    {
        foreach ($emailMessages as $emailMessage) {
            $this->bus->dispatch($emailMessage);
        }
    }

    public function dispatch(EmailMessage $emailMessage): void
    {
        $this->bus->dispatch($emailMessage);
    }
}
