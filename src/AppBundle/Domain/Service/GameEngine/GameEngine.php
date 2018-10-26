<?php

namespace AppBundle\Domain\Service\GameEngine;

use AppBundle\Domain\Entity\Game\Game;
use AppBundle\Domain\Entity\Ghost\Ghost;
use AppBundle\Domain\Entity\Maze\MazeCell;
use AppBundle\Domain\Entity\Position\Position;
use AppBundle\Domain\Service\MoveGhost\MoveGhostException;
use AppBundle\Domain\Service\MoveGhost\MoveGhostFactory;
use AppBundle\Domain\Service\MovePlayer\MoveAllPlayersServiceInterface;
use AppBundle\Domain\Service\MovePlayer\MovePlayerException;
use Psr\Log\LoggerInterface;

/**
 * Class GameEngine
 *
 * @package AppBundle\Domain\Service\GameEngine
 */
class GameEngine
{
    /** @var  MoveAllPlayersServiceInterface */
    protected $moveAllPlayersService;

    /** @var  MoveGhostFactory */
    protected $moveGhostFactory;

    /** @var LoggerInterface */
    protected $logger;

    /** @var int Score constants  */
    const SCORE_KILL_PLAYER = 100;
    const SCORE_KILL_GHOST = 50;
    const SCORE_DEAD = -25;

    /**
     * GameEngine constructor.
     *
     * @param MoveAllPlayersServiceInterface $moveAllPlayersService
     * @param MoveGhostFactory $moveGhostFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        MoveAllPlayersServiceInterface $moveAllPlayersService,
        MoveGhostFactory $moveGhostFactory,
        LoggerInterface $logger
    ) {
        $this->moveAllPlayersService = $moveAllPlayersService;
        $this->moveGhostFactory = $moveGhostFactory;
        $this->logger = $logger;
    }

    /**
     * Resets the game
     *
     * @param Game $game
     * @return $this
     */
    public function reset(Game &$game)
    {
        $game->resetPlaying();
        $this->createGhosts($game);
        return $this;
    }

    /**
     * Move all the players and ghosts of a game
     *
     * @param Game $game
     * @return bool TRUE if the game is not finished
     */
    public function move(Game &$game)
    {
        $this->movePlayers($game);
        $this->moveGhosts($game);
        $this->createGhosts($game);

        $game->incMoves();
        if (!$game->finished()) {
            return false;
        }

        return true;
    }

    /**
     * Move all the players
     *
     * @param Game $game
     * @return $this
     */
    protected function movePlayers(Game &$game)
    {
        try {
            $this->moveAllPlayersService->move($game);
        } catch (MovePlayerException $exc) {
            $this->logger->error('Error moving players in class: ' . get_class($this->moveAllPlayersService));
            $this->logger->error($exc);
        }
        return $this;
    }

    /**
     * Move all the ghosts
     *
     * @param Game $game
     * @return $this
     */
    protected function moveGhosts(Game &$game)
    {
        /** @var Ghost[] $ghosts */
        $ghosts = $game->ghosts();
        shuffle($ghosts);

        foreach ($ghosts as $ghost) {
            if (!$this->checkGhostKill($ghost, $game)) {
                try {
                    $moverService = $this->moveGhostFactory->locate($ghost);
                    if ($moverService->move($ghost, $game)) {
                        $this->checkGhostKill($ghost, $game);
                    }
                } catch (MoveGhostException $exc) {
                    $this->logger->error('Error moving ghost.');
                    $this->logger->error($exc);
                }
            }
        }

        return $this;
    }

    /**
     * Checks if a ghost killed a player. If a player is killed the ghost also dies.
     *
     * @param Ghost $ghost
     * @param Game  $game
     * @return bool true if the ghost still alive, false in other case
     */
    protected function checkGhostKill(Ghost $ghost, Game& $game)
    {
        if ($ghost->isNeutral()) {
            return false;
        }

        $players = $game->players();
        shuffle($players);

        foreach ($players as $player) {
            if (!$player->isKilled() && $player->position()->equals($ghost->position())) {
                $game->removeGhost($ghost);
                if ($player->isPowered()) {
                    $player->addScore(self::SCORE_KILL_GHOST);
                } else {
                    $player->killed()->addScore(self::SCORE_DEAD);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Create new ghost if ghost rate reached or not enough ghosts
     *
     * @param Game $game
     * @return $this
     */
    protected function createGhosts(Game &$game)
    {
        $minGhosts = $game->minGhosts();
        $ghostRate = $game->ghostRate();
        if ($ghostRate > 0) {
            $minGhosts += (int)($game->moves() / $ghostRate);
        }

        $ghostCount = count($game->ghosts());
        while ($ghostCount < $minGhosts) {
            $this->createNewGhost($game);
            $ghostCount++;
        }

        return $this;
    }

    /**
     * Create new ghost
     *
     * @param Game $game
     * @param int $type
     * @return $this
     */
    protected function createNewGhost(Game &$game, $type = Ghost::TYPE_RANDOM)
    {
        $maze = $game->maze();
        do {
            $y = rand(1, $maze->height() - 2);
            $x = rand(1, $maze->width() - 2);
        } while ($maze[$y][$x]->getContent() != MazeCell::CELL_EMPTY);
        $game->addGhost(new Ghost(new Position($y, $x), null, $type));
        return $this;
    }
}
