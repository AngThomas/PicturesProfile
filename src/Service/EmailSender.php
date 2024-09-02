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

    public function sendEmailsInBatch(array $recipients, string $sender, string $subject, string $body, int $batchSize): void
    {
        $emailMessages = [];

        foreach ($recipients as $recipient) {
            $emailMessages[] = new EmailMessage(
                $sender,
                $recipient,
                $subject,
                $body
            );

            if (count($emailMessages) === $batchSize) {
                $this->send($emailMessages);
                $emailMessages = [];
            }
        }

        if ($emailMessages) {
            $this->send($emailMessages);
        }
    }

    /**
     * @param EmailMessage[] $emailMessages
     */
    public function send(array $emailMessages): void
    {
        foreach ($emailMessages as $emailMessage) {
            $this->bus->dispatch($emailMessage);
        }
    }
}
