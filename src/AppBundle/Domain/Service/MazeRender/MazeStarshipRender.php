<?php

namespace AppBundle\Domain\Service\MazeRender;

/**
 * Class MazeStarshipRender
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
class MazeStarshipRender extends MazeIconRender
{
    protected function getMazeBackgroundCss(bool $finished)
    {
        if ($finished) {
            return 'x-starship-finished';
        } else {
            return 'x-starship-background';
        }
    }

    protected function getMazeWallCss($index)
    {
        return 'x-starship-wall';
    }

    protected function getPlayerCss($index, $direction)
    {
        return 'x-starship-player' . $index . '-' . $direction;
    }

    protected function getPlayedKilledCss($index, $direction)
    {
        return 'x-starship-player-explosion';
    }

    protected function getGhostNeutralCss($index, $direction, $display)
    {
        return 'x-starship-invader' . $display . '-neutral';
    }

    protected function getGhostCss($index, $direction, $display)
    {
        return 'x-starship-invader' . $display . '-regular';
    }

    protected function getGhostAngryCss($index, $direction, $display)
    {
        return 'x-starship-invader' . $display . '-angry';
    }

    protected function getGhostKilledCss($index, $direction, $display)
    {
        return 'x-starship-invader-explosion';
    }

    protected function getShotDirCss($direction)
    {
        return 'x-starship-shot-' . $direction;
    }
}
