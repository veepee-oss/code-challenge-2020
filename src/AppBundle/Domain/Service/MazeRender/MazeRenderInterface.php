<?php

namespace AppBundle\Domain\Service\MazeRender;

use AppBundle\Domain\Entity\Game\Game;

/**
 * Class MazeRenderInterface
 *
 * @package AppBundle\Domain\Service\MazeRender
 */
interface MazeRenderInterface
{
    /**
     * Renders the game's maze with all the players
     *
     * @param Game $game
     * @return string
     */
    public function render(Game $game) : string;

    /**
     * @return string the name of the renderer.
     */
    public function getName() : string;

    /**
     * Return the style name to use as background body
     *
     * @return string
     */
    public function getBackgroundCss() : string;

    /**
     * Return the style name to print a static player for the scoreboard
     *
     * @param int $index the number of player: 1, 2, 3, ...
     * @return string
     */
    public function getStaticPlayerCss(int $index) : string;
}
