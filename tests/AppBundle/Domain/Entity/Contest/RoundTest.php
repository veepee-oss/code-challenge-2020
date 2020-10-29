<?php

namespace Tests\AppBundle\Domain\Entity\Contest;

use AppBundle\Domain\Entity\Contest\Competitor;
use AppBundle\Domain\Entity\Contest\Participant;
use AppBundle\Domain\Entity\Contest\Round;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for domain entity Round
 *
 * @package Tests\AppBundle\Domain\Entity\Contest
 */
class RoundTest extends TestCase
{
    /**
     * calculateClassification()
     * - 44 competitors with different scores
     * - 15 natural winners + 3 from playoffs = 18 winners
     */
    public function testCalculateClassificationWhenDifferentScoresMaximizesTheWinners()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 44; $i++) {
            $participants[] = $this->newParticipant($i + 1, $contest, 10 * $i);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(18, count($winners));
    }

    /**
     * calculateClassification()
     * - 44 competitors. Scores: incremental for the first 15 competitors (100, 200, 300, ...), the rest 50 points
     * - 15 natural winners + 0 from playoffs = 18 winners
     */
    public function testCalculateClassificationWhenDifferentScoresManyTiePlayoffsReducesTheWinners()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 44; $i++) {
            $score = ($i < 15) ? 100 * ($i + 1) : 50;
            $participants[] = $this->newParticipant($i + 1, $contest, $score);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(15, count($winners));
    }

    /**
     * calculateClassification()
     * - 44 competitors. Scores: incremental for 14 competitors (100, 200, ...), 6 at 50 points, the rest 25 points
     * - 15 natural winners + 5 from ties + 0 from playoffs = 20 winners
     */
    public function testCalculateClassificationWhenDifferentScoresWithManyTiesIncreasesTheWinners()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 44; $i++) {
            $score = ($i < 14) ? 100 * ($i + 1) : ($i < 20) ? 50 : 25;
            $participants[] = $this->newParticipant($i + 1, $contest, $score);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(20, count($winners));
    }

    /**
     * calculateClassification()
     * - 25 competitors with the same score
     * - 9 natural winners + 16 tie = 25 winners
     */
    public function testCalculateClassificationWhenSameScoreEverybodyWin()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 25; $i++) {
            $participants[] = $this->newParticipant($i + 1, $contest, 100);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(25, count($winners));
    }

    /**
     * calculateClassification()
     * - 15 competitors
     * - 6 natural winners + 3 playoffs
     */
    public function testCalculateClassificationWith15CompetitorsGenerates9Winners()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 15; $i++) {
            $participants[] = $this->newParticipant($i + 1, $contest, $i * 100);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(9, count($winners));
    }

    /**
     * calculateClassification()
     * - 9 competitors
     * - 3 natural winners + 0 playoffs
     */
    public function testCalculateClassificationWith9CompetitorsGenerates3Winners()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 9; $i++) {
            $participants[] = $this->newParticipant($i + 1, $contest, $i * 100);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(3, count($winners));
    }

    /**
     * calculateClassification()
     * - 4 competitors
     * - 3 natural winners + 0 playoffs
     */
    public function testCalculateClassificationWith4CompetitorsGenerates3Winners()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 4; $i++) {
            $participants[] = $this->newParticipant($i + 1, $contest, $i * 100);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(3, count($winners));
    }

    /**
     * calculateClassification()
     * - 3 competitors
     * - 1 natural winners + 0 playoffs
     */
    public function testCalculateClassificationWith3CompetitorsGenerates1Winner()
    {
        $contest = "c01";
        $participants = [];
        for ($i = 0; $i < 3; $i++) {
            $participants[] = $this->newParticipant($i + 1, $contest, $i * 100);
        }

        $round = $this->newRound("r01", $contest, $participants);
        $round->calculateClassification();
        $winners = $round->winners();

        $this->assertEquals(1, count($winners));
    }

    /**
     * Creates a participant
     *
     * @param int $num
     * @param string $contest
     * @param int $score
     * @return Participant
     * @throws \Exception
     */
    private function newParticipant(int $num, string $contest, int $score): Participant
    {
        $name = sprintf("p%02d", $num);

        $competitor = new Competitor(
            $name,
            $contest,
            $name,
            $name,
            $name,
            true,
            null
        );

        return new Participant(
            $competitor,
            $score,
            null
        );
    }

    /**
     * Creates a new round
     *
     * @param string $name
     * @param string $contest
     * @param array $participants
     * @return Round
     * @throws \Exception
     */
    private function newRound(string $name, string $contest, array $participants): Round
    {
        return new Round(
            $name,
            $contest,
            $name,
            Round::STATUS_FINISHED,
            15,
            15,
            0,
            0,
            50,
            1,
            $participants
        );
    }
}
