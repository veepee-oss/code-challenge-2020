<?php

namespace AppBundle\Domain\Service\MazeRender;

/**
 * Class MazePacmanRender
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
class MazePacmanRender extends MazeIconRender
{
    public function getMazeGlobalCss() : string
    {
        return 'x-maze';
    }

    public function getMazeBackgroundCss(bool $finished) : string
    {
        if ($finished) {
            return 'x-finished';
        } else {
            return 'x-background';
        }
    }

    public function getEmptyCellCss() : string
    {
        return 'x-empty';
    }

    public function getMazeWallCss($type) : string
    {
        return 'x-wall';
    }

    public function getPlayerCss($index, $direction) : string
    {
        return 'x-player' . $index . '-' . $direction;
    }

    public function getPlayedKilledCss($index, $direction) : string
    {
        return 'x-killed' . $index;
    }

    public function getEnemyRegularCss($index, $direction) : string
    {
        return 'x-ghost';
    }

    public function getEnemyNeutralCss($index, $direction) : string
    {
        return 'x-ghost-neutral';
    }

    public function getEnemyAngryCss($index, $direction) : string
    {
        return 'x-ghost-bad';
    }

    public function getEnemyKilledCss($index, $direction)
    {
        return 'x-ghost-killed';
    }

    public function getShotDirCss($direction) : string
    {
        return 'x-shot';
    }
}
