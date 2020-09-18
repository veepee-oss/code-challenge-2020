<?php

namespace AppBundle\Domain\Service\MazeRender;

/**
 * Class MazeHalloweenRender
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
class MazeHalloweenRender extends MazeIconRender
{
    protected function getMazeBackgroundCss(bool $finished)
    {
        if ($finished) {
            return 'x-halloween-finished';
        } else {
            return 'x-halloween-background';
        }
    }

    protected function getMazeWallCss($index)
    {
        $index %= 3; // Max 3 different walls
        return 'x-halloween-wall' . $index;
    }

    protected function getPlayerCss($index, $direction)
    {
        return 'x-starship-player' . $index . '-' . $direction;
    }

    protected function getPlayedKilledCss($index, $direction)
    {
        return 'x-starship-player-explosion';
    }

    protected function getGhostCss($index, $direction, $display)
    {
        return 'x-halloween-enemy' . $display . '-regular';
    }

    protected function getGhostNeutralCss($index, $direction, $display)
    {
        return 'x-halloween-enemy' . $display . '-neutral';
    }

    protected function getGhostAngryCss($index, $direction, $display)
    {
        return $this->getGhostCss($index, $direction, $display);
    }

    protected function getGhostKilledCss($index, $direction, $display)
    {
        return 'x-halloween-enemy-killed';
    }

    protected function getShotDirCss($direction)
    {
        return 'x-starship-shot-' . $direction;
    }
}
