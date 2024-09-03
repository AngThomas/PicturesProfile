<?php

namespace App\Model\Email;

class EmailData
{
    private string $sender;
    private array $recipients;
    private string $subject;
    private string $body;

    public function __construct(string $sender, array $recipients, string $subject, string $body)
    {
        $this->sender = $sender;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients(array $recipients): self
    {
        $this->recipients = $recipients;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return EmailMessage[]
     */
    public function createEmailMessages(): array
    {
        $emailMessages = [];

        foreach ($this->recipients as $recipient) {
            $emailMessages[] = new EmailMessage(
                $this->sender,
                $recipient,
                $this->subject,
                $this->body
            );
        }

        return $emailMessages;
    }
}
