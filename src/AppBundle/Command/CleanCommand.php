<?php

namespace AppBundle\Command;

use AppBundle\Entity\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:clean')
            ->setDescription('Remove given amount of old messages.')
            ->addArgument(
                'amount',
                InputArgument::REQUIRED,
                'Amount of old messages to remove'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('amount');
        $amount = intval($argument);

        if ($amount > 0) {
            /** @var \Doctrine\Common\Persistence\ObjectManager $em */
            $em = $this->getContainer()->get('doctrine')->getManager();

            /** @var MessageRepository $messageRepository */
            $messageRepository = $em->getRepository('AppBundle:Message');

            $deleted = $messageRepository->removeOld($amount);

            $output->writeln('Successfully deleted ' . $deleted . ' messages');
        } else {
            $output->writeln('Error: incorrect amount ' . $argument);
        }
    }
}