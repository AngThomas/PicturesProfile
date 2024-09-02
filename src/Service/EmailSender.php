<?php

namespace App\Service;

use App\Model\EmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailSender
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param EmailMessage[] $emailMessages
     */
    public function sendEmail(array $emailMessages): void
    {
        foreach ($emailMessages as $emailMessage) {
            $this->bus->dispatch($emailMessage);
        }
    }
}
