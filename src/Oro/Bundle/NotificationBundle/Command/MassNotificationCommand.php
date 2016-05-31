<?php

namespace  Oro\Bundle\NotificationBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class MassNotificationCommand
 * Console command implementation
 *
 * @package Oro\Bundle\NotificationBundle\Command
 */
class MassNotificationCommand extends ContainerAwareCommand
{
    const COMMAND_NAME = 'oro:mass_notification:send';

    /**
     * Console command configuration
     */
    public function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Send mass notifications to users')
            ->addOption(
                'subject',
                null,
                InputOption::VALUE_OPTIONAL,
                'Subject of notification email. If emtpy, subject from the configured template is used'
            )
            ->addOption(
                'message',
                null,
                InputOption::VALUE_OPTIONAL,
                'Notification message to send'
            )
            ->addOption(
                'sender_name',
                null,
                InputOption::VALUE_OPTIONAL,
                'Notification sender name'
            )
            ->addOption(
                'sender_email',
                null,
                InputOption::VALUE_OPTIONAL,
                'Notification sender email'
            );
    }

    /**
     * Runs command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->getDescription());
        $subject     = $input->getOption('subject');
        $message     = $input->getOption('message');
        $senderName  = $input->getOption('sender_name');
        $senderEmail = $input->getOption('sender_email');

        $service = $this->getContainer()->get('oro_notification.mass_notification_sender');

        $count = $service->send($message, $subject, $senderEmail, $senderName);

        $output->writeln(sprintf('%s notifications have been added to the queue', $count));
    }
}
