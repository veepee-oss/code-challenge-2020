<?php

namespace AppBundle\Command;

use AppBundle\Domain\Entity\Game\Game;
use AppBundle\Domain\Entity\Stats\Stats;
use AppBundle\Domain\Service\GameEngine\GameDaemonManagerInterface;
use AppBundle\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command generate stats for the game
 *
 * @package AppBundle\Command
 */
class ShowGameStatsCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('app:stats:show')
            ->setDescription('Shows statistics of the game.')
            ->addArgument('days', InputArgument::OPTIONAL, 'Days range.', 15);
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
        $stats = new Stats();

        /** @var \AppBundle\Repository\GameRepository $repo */
        $repo = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Game');

        $limit = 50;
        $total = $repo->count([]);
        $interval = $input->getArgument('days');

        for ($offset = 0; $offset < $total; $offset += $limit) {

            /** @var \AppBundle\Entity\Game[] $entities */
            $entities = $repo->findBy([], [ 'id' => 'desc' ], $limit, $offset);

            /** @var Game[] $games */
            $games = [];
            foreach ($entities as $entity) {
                $games[] = $entity->toDomainEntity();
            }

            $stats->addGames($games, $interval);
        }

        $output->writeln('Num test games: ' . $stats->numTestGames());
        $output->writeln('');

        $output->writeln('Num different APIs: ' . count($stats->apis()));
        foreach ($stats->apis() as $api => $count) {
            $output->writeln("\t" . '- ' . $api . ' (' . $count . ' games)');
        }
        $output->writeln('');

        $output->writeln('Num different Emails: ' . count($stats->emails()));
        foreach ($stats->emails() as $email => $count) {
            $output->writeln("\t" . '- ' . $email . ' (' . $count . ' games)');
        }
        $output->writeln('');

        $output->writeln('Games distribution per hours:');
        foreach ($stats->hours() as $hour => $count) {
            $output->writeln("\t" . '- ' . $hour . ' (' . $count . ' games)');
        }
        $output->writeln('');

        $output->writeln('Games distribution per days:');
        foreach ($stats->days() as $day => $count) {
            $output->writeln("\t" . '- ' . $day . ' (' . $count . ' games)');
        }
        $output->writeln('');

        return 0;
    }
}
