<?php

namespace App\Command;

use App\Model\EmailMessage;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'SendNewsletter',
    description: 'Add a short description for your command',
)]
class SendNewsletterCommand extends Command
{
    private EmailSender $emailSender;
    private UserRepository $userRepository;

    public function __construct(
        EmailSender $emailSender,
        UserRepository $userRepository,
        ?string $name = null,
    ) {
        $this->emailSender = $emailSender;
        $this->userRepository = $userRepository;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $emailMessages = [];
        $subject = 'Your best newsletter';
        $messageBody = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id interdum nibh. Phasellus blandit tortor in cursus convallis. Praesent et tellus fermentum, pellentesque lectus at, tincidunt risus. Quisque in nisl malesuada, aliquet nibh at, molestie libero.';

        $page = 1;
        $batchSize = 500;

        do {
            $newActiveUsers = $this->userRepository->findActiveUsersByDate(new \DateTimeImmutable(), $page, $batchSize);
            foreach ($newActiveUsers as $newActiveUser) {
                $emailMessages[] = new EmailMessage(
                    $newActiveUser['email'],
                    $subject,
                    $messageBody
                );
            }

            if (count($emailMessages) >= $batchSize) {
                $this->emailSender->sendEmail($emailMessages);
                $emailMessages = [];
            }

            ++$page;
        } while (count($newActiveUsers) === $batchSize);

        if (count($emailMessages) > 0) {
            $this->emailSender->sendEmail($emailMessages);
        }

        return self::SUCCESS;
    }
}
