<?php

namespace AppBundle\Domain\Service\MazeRender;

/**
 * Class MazeStarshipRender
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
class MazeStarshipRender extends MazeIconRender
{
    public function getName(): string
    {
        return 'starship';
    }

    public function getBackgroundCss(): string
    {
        return "x-starship-background";
    }

    public function getMazeGlobalCss() : string
    {
        return 'x-maze x-maze-starship';
    }

    public function getMazeBackgroundCss(bool $finished) : string
    {
        return ($finished) ? 'x-finished' : '';
    }

    public function getEmptyCellCss() : string
    {
        return 'x-empty';
    }

    public function getMazeWallCss($type) : string
    {
        return 'x-starship-wall';
    }

    public function getPlayerCss($index, $direction) : string
    {
        return 'x-starship-player' . $index . '-' . $direction;
    }

    public function getPlayedKilledCss($index, $direction) : string
    {
        return 'x-starship-player-explosion';
    }

    public function getEnemyNeutralCss($index, $direction) : string
    {
        return 'x-starship-invader' . $index . '-neutral';
    }

    public function getEnemyRegularCss($index, $direction) : string
    {
        return 'x-starship-invader' . $index . '-regular';
    }

    public function getEnemyAngryCss($index, $direction) : string
    {
        return 'x-starship-invader' . $index . '-angry';
    }

    public function getEnemyKilledCss($index, $direction)
    {
        return 'x-starship-invader-explosion';
    }

    public function getShotDirCss($direction) : string
    {
        return 'x-starship-shot-' . $direction;
    }
}
