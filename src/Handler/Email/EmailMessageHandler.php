<?php

namespace App\Handler\Email;

use App\Model\Email\EmailMessage;
use Symfony\Component\Mailer\Exception\RuntimeException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class EmailMessageHandler
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(EmailMessage $emailMessage)
    {
        $email = (new Email())
            ->from($emailMessage->getSender()) // Replace with your sender email
            ->to($emailMessage->getRecipient())
            ->subject($emailMessage->getSubject())
            ->text($emailMessage->getBody())
            ->priority(Email::PRIORITY_HIGH);

        try {
            $this->mailer->send($email);
        } catch(Throwable $throwable) {
            throw new RuntimeException('Email could not be send.', 500);
        }

    }
}
