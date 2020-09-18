<?php

namespace Tests\AppBundle\Domain\Entity\Maze;

use AppBundle\Domain\Entity\Maze\MazeCell;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for domain entity MazeCell
 *
 * @package Tests\AppBundle\Domain\Entity\Maze
 */
class MazeCellTest extends TestCase
{
    /**
     * Test get cell content
     */
    public function testGetContent()
    {
        $cell = new MazeCell(3);
        $this->assertEquals(3, $cell->getContent());
    }

    /**
     * Test set cell content
     */
    public function testSetContent()
    {
        $cell = new MazeCell(5);
        $this->assertEquals(5, $cell->getContent());

        $cell->setContent(9);
        $this->assertEquals(9, $cell->getContent());
    }

    /**
     * Test cell is empty when the content is 0
     */
    public function testIsEmpty()
    {
        $cell = new MazeCell(0);
        $this->assertTrue($cell->isEmpty());
    }

    /**
     * Test cell is empty when the content isn't 0
     */
    public function testIsNotEmpty()
    {
        $cell = new MazeCell(7);
        $this->assertFalse($cell->isEmpty());
    }

    /**
     * Test newEmptyCell creates an empty cell
     */
    public function testNewEmptyCell()
    {
        $cell = MazeCell::newEmptyCell();
        $this->assertTrue($cell->isEmpty());
    }

    /**
     * Test newWallCell creates a wall cell
     */
    public function testNewWallCell()
    {
        $cell = MazeCell::newWallCell();
        $this->assertTrue($cell->isWall());
    }

    /**
     * Test setEmpty sets the cell empty
     */
    public function testSetEmpty()
    {
        $cell = new MazeCell(5);
        $cell->setEmpty();
        $this->assertTrue($cell->isEmpty());
    }

    /**
     * Test setWall sets the cell wall
     */
    public function testSetWall()
    {
        $cell = new MazeCell(5);
        $cell->setWall();
        $this->assertTrue($cell->isWall());
    }

    /**
     * Test getWallIndex return an index
     */
    public function testGetWallIndex0()
    {
        $cell = new MazeCell(0x80);
        $this->assertEquals(0, $cell->getWallIndex());
    }

    /**
     * Test getWallIndex return an index
     */
    public function testGetWallIndex1()
    {
        $cell = new MazeCell(0x81);
        $this->assertEquals(1, $cell->getWallIndex());
    }

    /**
     * Test getWallIndex return an index
     */
    public function testGetWallIndex5()
    {
        $cell = new MazeCell(0x85);
        $this->assertEquals(5, $cell->getWallIndex());
    }
}
