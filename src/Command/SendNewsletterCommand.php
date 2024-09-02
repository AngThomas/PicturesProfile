<?php

namespace App\Command;

use App\Model\EmailMessage;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'SendNewsletter',
    description: 'Sends the newsletter to new active users.',
)]
class SendNewsletterCommand extends Command
{
    private EmailSender $emailSender;
    private UserRepository $userRepository;
    private const BATCH_SIZE = 500;

    public function __construct(
        EmailSender $emailSender,
        UserRepository $userRepository,
    ) {
        parent::__construct();
        $this->emailSender = $emailSender;
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $subject = 'Your best newsletter';
        $messageBody = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id interdum nibh. Phasellus blandit tortor in cursus convallis. Praesent et tellus fermentum, pellentesque lectus at, tincidunt risus. Quisque in nisl malesuada, aliquet nibh at, molestie libero.';

        $page = 1;

        do {
            $newActiveUsers = $this->userRepository->findActiveUsersByDate(new \DateTimeImmutable(), $page, self::BATCH_SIZE);
            $this->emailSender->sendEmailsInBatch(array_column($newActiveUsers, 'email'), 'no-reply@cobbleweb.com', $subject, $messageBody, self::BATCH_SIZE);
            ++$page;
        } while (self::BATCH_SIZE === count($newActiveUsers));

        return Command::SUCCESS;
    }

}
