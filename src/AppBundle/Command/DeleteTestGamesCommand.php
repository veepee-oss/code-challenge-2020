<?php

namespace AppBundle\Command;

use AppBundle\Domain\Entity\Game\Game;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Command to delete the test games to free up space
 *
 * @package AppBundle\Command
 */
class DeleteTestGamesCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('app:test-games:delete')
            ->setDescription('Deletes the test games.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Forces the action.');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws LogicException When this abstract method is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('This command deletes all the test games to free up space in the server.');

        $force = $input->getOption('force');
        if (!$force) {
            $output->writeln('');
            $output->write('<comment>For security reasons, you must use the </comment>');
            $output->write('--force');
            $output->writeln('<comment> option to run the command.</comment>');
            $output->writeln('');
            return 1;
        }

        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();

        /** @var \AppBundle\Repository\GameRepository $repo */
        $repo = $em->getRepository('AppBundle:Game');

        $total = $repo->count([]);

        $output->writeln('');
        $output->writeln('<info>Checking </info>' . $total . '<info> games...</info>');

        $deleted = 0;
        $count = 0;

        /** @var \AppBundle\Entity\Game[] $entities */
        $entities = $repo->findBy([], [ 'id' => 'desc' ]);
        foreach ($entities as $entity) {
            ++$count;

            /** @var Game $game */
            $game = $entity->toDomainEntity();
            if (null == $game->matchUUid()) {
                $output->write("\r" . $count . '. Removing game ' . $game->uuid() . '...');
                $repo->removeGame($entity);
                $em->flush();
                ++$deleted;
            }
        }

        $output->write("\r" . '- Removing game ' . $game->uuid() . '...');
        $output->writeln('');
        $output->writeln($deleted . ' <info>games deleted!</info>');
        $output->writeln('');

        return 0;
    }
}
