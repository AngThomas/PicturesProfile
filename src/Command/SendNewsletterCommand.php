<?php

namespace App\Command;

use App\Model\Email\EmailData;
use App\Repository\UserRepository;
use App\Service\EmailDispatcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'SendNewsletter',
    description: 'Sends the newsletter to new active users.',
)]
class SendNewsletterCommand extends Command
{
    private EmailDispatcher $emailDispatcher;
    private UserRepository $userRepository;

    public function __construct(
        EmailDispatcher $emailDispatcher,
        UserRepository $userRepository,
    ) {
        parent::__construct();
        $this->emailDispatcher = $emailDispatcher;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('sender', InputArgument::REQUIRED, 'Email address of the sender.')
            ->addArgument('subject', InputArgument::REQUIRED, 'Subject of the newsletter.')
            ->addArgument('body', InputArgument::REQUIRED, 'Body of the newsletter.')
            ->addArgument('batchSize', InputArgument::OPTIONAL, 'Number of users to process per batch.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sender = $input->getArgument('sender');
        $subject = $input->getArgument('subject');
        $body = $input->getArgument('body');
        $batchSize = null !== $input->getArgument('batchSize') ? (int) $input->getArgument('batchSize') : 100;
        $page = 1;

        do {
            $newActiveUsers = $this->userRepository->findActiveUsersByDate(new \DateTimeImmutable(), $page, $batchSize);

            $emailMessages = (new EmailData(
                $sender,
                array_column($newActiveUsers, 'email'),
                $subject,
                $body
            ))->createEmailMessages();

            $this->emailDispatcher->dispatchMulti($emailMessages);
            ++$page;
        } while ($batchSize === count($newActiveUsers));

        return Command::SUCCESS;
    }
}
