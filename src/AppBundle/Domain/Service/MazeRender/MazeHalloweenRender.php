<?php

namespace AppBundle\Domain\Service\MazeRender;

/**
 * Class MazeHalloweenRender
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
class MazeHalloweenRender extends MazeIconRender
{
    public function getName(): string
    {
        return 'halloween';
    }

    public function getBackgroundCss(): string
    {
        return "x-halloween-background";
    }

    public function getMazeGlobalCss() : string
    {
        return 'x-maze x-halloween-maze';
    }

    public function getMazeBackgroundCss(bool $finished) : string
    {
        return '';
    }

    public function getEmptyCellCss() : string
    {
        return 'x-empty';
    }

    public function getMazeWallCss($type) : string
    {
        $type %= 3; // Max 3 different types of walls
        return 'x-halloween-wall' . $type;
    }

    public function getPlayerCss($index, $direction) : string
    {
        return 'x-halloween-player' . $index . '-' . $direction;
    }

    public function getPlayedKilledCss($index, $direction) : string
    {
        return 'x-halloween-player-explosion';
    }

    public function getEnemyRegularCss($index, $direction) : string
    {
        return 'x-halloween-enemy' . $index . '-regular';
    }

    public function getEnemyNeutralCss($index, $direction) : string
    {
        return 'x-halloween-enemy' . $index . '-neutral';
    }

    public function getEnemyAngryCss($index, $direction) : string
    {
        return $this->getEnemyRegularCss($index, $direction);
    }

    public function getEnemyKilledCss($index, $direction)
    {
        return 'x-halloween-enemy-killed';
    }

    public function getShotDirCss($direction) : string
    {
        return 'x-halloween-lightning-bolt-' . $direction;
    }
}
