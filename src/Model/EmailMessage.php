<?php

namespace App\Model;

class EmailMessage
{
    private string $sender;
    private string $recipient;
    private string $subject;
    private string $body;

    public function __construct(string $sender, string $recipient, string $subject, string $body)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
