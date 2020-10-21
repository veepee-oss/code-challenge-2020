<?php

namespace AppBundle\Domain\Entity\Stats;

use AppBundle\Domain\Entity\Game\Game;

/**
 * Domain entity Stats
 *
 * @package AppBundle\Domain\Entity\Stats
 */
class Stats
{
    /** @var int */
    private $numTestGames;

    /** @var array string => int */
    private $emails;

    /** @var array string => int */
    private $apis;

    /**
     * Constructor.
     *
     * @param Game[] $games
     */
    public function __construct(array $games = [])
    {
        $this->reset();
        $this->addGames($games);
    }

    /**
     * Resets the stats
     *
     * @return Stats
     */
    public function reset(): Stats
    {
        $this->numTestGames = 0;
        $this->emails = [];
        $this->apis = [];
        return $this;
    }

    /**
     * Adds games to the stats object
     *
     * @param Game[] $games
     * @return $this
     */
    public function addGames(array $games): Stats
    {
        foreach ($games as $game) {
            if (!$game->matchUUid()) {
                $this->numTestGames++;
                $localEmails = [];
                $localApis = [];
                foreach ($game->players() as $player) {
                    $email = $player->email();
                    if (false === array_search($email, $localEmails)) {
                        if (!array_key_exists($email, $this->emails)) {
                            $this->emails[$email] = 0;
                        }
                        $this->emails[$email]++;
                        $localEmails[] = $email;
                    }

                    $api = $player->url();
                    if (false === array_search($api, $localApis)) {
                        if (!array_key_exists($api, $this->apis)) {
                            $this->apis[$api] = 0;
                        }
                        $this->apis[$api]++;
                        $localApis[] = $api;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @return int
     */
    public function numTestGames(): int
    {
        return $this->numTestGames;
    }

    /**
     * @return array
     */
    public function emails(): array
    {
        $copy = $this->emails;
        arsort($copy, SORT_NUMERIC);
        return $copy;
    }

    /**
     * @return array
     */
    public function apis(): array
    {
        $copy = $this->apis;
        arsort($copy, SORT_NUMERIC);
        return $copy;
    }
}
