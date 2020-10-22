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

    private static $excludedApis = [
        'http://localhost/api'
    ];

    /** @var array string => int */
    private $hours;

    /** @var array string => int */
    private $days;

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
        $this->hours = [];
        $this->days = [];
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
                $this->generatePlayerStats($game);
                $this->generateHoursStats($game);
                $this->generateDaysStats($game);
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

    /**
     * @return array
     */
    public function hours(): array
    {
        $copy = $this->hours;
        ksort($copy, SORT_STRING);
        return $copy;
    }

    /**
     * @return array
     */
    public function days(): array
    {
        $copy = $this->days;
        ksort($copy, SORT_STRING);
        return $copy;
    }

    private function generatePlayerStats(Game $game): void
    {
        $localEmails = [];
        $localApis = [];

        foreach ($game->players() as $player) {
            $api = $player->url();
            $email = $player->email();

            if (false === array_search($api, self::$excludedApis)) {

                if (false === array_search($api, $localApis)) {
                    if (!array_key_exists($api, $this->apis)) {
                        $this->apis[$api] = 0;
                    }
                    $this->apis[$api]++;
                    $localApis[] = $api;
                }

                if (false === array_search($email, $localEmails)) {
                    if (!array_key_exists($email, $this->emails)) {
                        $this->emails[$email] = 0;
                    }
                    $this->emails[$email]++;
                    $localEmails[] = $email;
                }
            }
        }
    }

    private function generateHoursStats(Game $game): void
    {
        /** @var \DateTime $timestamp */
        $timestamp = $game->lastUpdatedAt();
        $hour = $timestamp->format("H:00-H:59");
        if (!array_key_exists($hour, $this->hours)) {
            $this->hours[$hour] = 0;
        }
        $this->hours[$hour]++;
    }

    private function generateDaysStats(Game $game): void
    {
        /** @var \DateTime $timestamp */
        $timestamp = $game->lastUpdatedAt();
        $day = $timestamp->format("Y-m-d");
        if (!array_key_exists($day, $this->days)) {
            $this->days[$day] = 0;
        }
        $this->days[$day]++;
    }
}
