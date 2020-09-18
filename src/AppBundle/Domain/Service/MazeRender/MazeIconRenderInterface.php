<?php

namespace AppBundle\Domain\Service\MazeRender;

/**
 * Interface MazeIconRender
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
interface MazeIconRenderInterface
{
    /**
     * @return string the global css class for the maze.
     */
    public function getMazeGlobalCss() : string;

    /**
     * @param bool $finished whether the game is finished or not.
     * @return string        the css class for the background.
     */
    public function getMazeBackgroundCss(bool $finished) : string;

    /**
     * @return string the css class to draw an empy cell.
     */
    public function getEmptyCellCss() : string;

    /**
     * @param int $type the type of wall (0, 1, 2, ...).
     * @return string   the css class to draw a wall.
     */
    public function getMazeWallCss($type) : string;

    /**
     * @param int    $index     the number of player: 1, 2, 3, ...
     * @param string $direction the direction where is facing: up, down, left, right.
     * @return string           the css class to draw a regular player.
     */
    public function getPlayerCss($index, $direction) : string;

    /**
     * @param int $index        the number of player: 1, 2, 3, ...
     * @param string $direction the direction where is facing: up, down, left, right.
     * @return string           the css class to draw a killed player.
     */
    public function getPlayedKilledCss($index, $direction) : string;

    /**
     * @param int    $index     the number of enemy: 1, 2, 3, ...
     * @param string $direction the direction where is facing: up, down, left, right.
     * @return string           the css class to draw a regular enemy.
     */
    public function getEnemyRegularCss($index, $direction) : string;

    /**
     * @param int    $index     the number of enemy: 1, 2, 3, ...
     * @param string $direction the direction where is facing: up, down, left, right.
     * @return string           the css class to draw a neutral enemy.
     */
    public function getEnemyNeutralCss($index, $direction) : string;

    /**
     * @param int    $index     the number of enemy: 1, 2, 3, ...
     * @param string $direction the direction where is facing: up, down, left, right.
     * @return string           the css class to draw an angry enemy.
     */
    public function getEnemyAngryCss($index, $direction) : string;

    /**
     * @param int    $index     the number of enemy: 1, 2, 3, ...
     * @param string $direction the direction where is facing: up, down, left, right.
     * @return string           the css class to draw a killed enemy.
     */
    public function getEnemyKilledCss($index, $direction);

    /**
     * @param string $direction the direction of the shot: up, down, left, right.
     * @return string           the css class to draw shot.
     */
    public function getShotDirCss($direction) : string;
}
