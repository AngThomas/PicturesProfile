<?php

namespace App\Handler\Email;

use App\Model\EmailMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class EmailMessageHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(EmailMessage $emailMessage)
    {
        $email = (new Email())
            ->from('no-reply@cobbleweb.com') // Replace with your sender email
            ->to($emailMessage->getRecipient())
            ->subject($emailMessage->getSubject())
            ->text($emailMessage->getBody())
            ->priority(Email::PRIORITY_HIGH);

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Handle exception if needed (log it, etc.)
            throw new \RuntimeException('Failed to send email to '.$emailMessage->getRecipient().': '.$e->getMessage());
        }
    }
}
