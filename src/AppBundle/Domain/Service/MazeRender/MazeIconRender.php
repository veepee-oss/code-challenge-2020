<?php

namespace AppBundle\Domain\Service\MazeRender;

use AppBundle\Domain\Entity\Game\Game;
use AppBundle\Domain\Entity\Ghost\Ghost;
use AppBundle\Domain\Entity\Position\Direction;
use AppBundle\Domain\Entity\Position\Position;

/**
 * Class MazeIconRender
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
abstract class MazeIconRender implements MazeRenderInterface, MazeIconRenderInterface
{
    /**
     * Renders the game's maze with all the players
     *
     * @param Game $game
     * @return string
     */
    public function render(Game $game) : string
    {
        $maze = $game->maze();
        $class = $this->getMazeGlobalCss()
            . ' ' . $this->getMazeBackgroundCss($game->finished());

        $html = '<table class="' . $class .'">';

        $rows = $maze->height();
        $cols = $maze->width();

        // For each row...
        for ($row = 0; $row < $rows; ++$row) {
            $html .= '<tr>';

            // For each column...
            for ($col = 0; $col < $cols; ++$col) {
                $class = null;
                $title = null;

                // Check if there is a player in this position
                foreach ($game->players() as $index => $player) {
                    if ($player->position()->x() == $col
                        && $player->position()->y() == $row) {
                        $direction = $player->direction();
                        if (!$direction) {
                            $direction = Direction::RIGHT;
                        }

                        if ($player->isKilled()) {
                            $class = $this->getPlayedKilledCss(1 + $index, $direction);
                            $title = $player->name();
                        } else {
                            $class = $this->getPlayerCss(1 + $index, $direction);
                            $title = $player->name();
                        }
                        break;
                    }
                }

                // Check if there is a ghost in this position
                if (null === $class) {
                    foreach ($game->ghosts() as $index => $ghost) {
                        if ($ghost->position()->x() == $col
                            && $ghost->position()->y() == $row) {
                            $direction = $ghost->direction();
                            if (!$direction) {
                                $direction = Direction::RIGHT;
                            }

                            if ($ghost->isNeutral()) {
                                $class = $this->getEnemyNeutralCss($ghost->display(), $direction);
                            } elseif (Ghost::TYPE_KILLING == $ghost->type()) {
                                $class = $this->getEnemyAngryCss($ghost->display(), $direction);
                            } else {
                                $class = $this->getEnemyRegularCss($ghost->display(), $direction);
                            }
                            break;
                        }
                    }
                }

                // Check if there is a wall in this position
                if (null === $class
                    && $maze[$row][$col]->isWall()) {
                    $class = $this->getMazeWallCss($maze[$row][$col]->getWallIndex());
                }

                // Check if there is killed ghost in this position
                if (null === $class) {
                    foreach ($game->killedGhosts() as $index => $ghost) {
                        if ($ghost->position()->x() == $col
                            && $ghost->position()->y() == $row) {
                            $class = $this->getEnemyKilledCss($ghost->display(), $ghost->direction() ?? Direction::RIGHT);
                            break;
                        }
                    }
                }

                // Check if there is a shot in this position
                if (null === $class) {
                    foreach ($game->players() as $player) {
                        $shotDir = $player->fireDirAtPosition(new Position($row, $col));
                        if ($shotDir) {
                            $class = $this->getShotDirCss($shotDir);
                        }
                    }
                }

                // Check if is an empty cell
                if (null === $class) {
                    $class = $this->getEmptyCellCss();
                }

                $html .= '<td ';
                if ($title) {
                    $html .= 'title="' . $title . '" ';
                }
                $html .= 'class="' . $class . '">';
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Return the default style name to use as background body
     *
     * @return string
     */
    public function getBackgroundCss(): string
    {
        return "";
    }

    /**
     * Return the style name to print a static player for the scoreboard
     *
     * @param int $index the number of player: 1, 2, 3, ...
     * @return string
     */
    public function getStaticPlayerCss(int $index) : string
    {
        return $this->getPlayerCss($index, 'right');
    }
}
