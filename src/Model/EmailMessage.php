<?php

namespace App\Model;

class EmailMessage
{
    private string $recipient;
    private string $subject;
    private string $body;

    public function __construct(string $recipient, string $subject, string $body)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->body = $body;
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
