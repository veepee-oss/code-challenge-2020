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
}
