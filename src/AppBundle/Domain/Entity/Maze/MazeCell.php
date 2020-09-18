<?php

namespace AppBundle\Domain\Entity\Maze;

/**
 * Domain entity MazeCell
 *
 * @package AppBundle\Domain\Entity\Maze
 */
class MazeCell
{
    private const CELL_EMPTY = 0x00;
    private const CELL_WALL_BASE = 0x80;
    private const MAX_RANDOM_WALLS = 3;

    /** @var int */
    protected $content;

    /**
     * MazeCell constructor.
     *
     * @param int $content
     */
    public function __construct(int $content)
    {
        $this->content = $content;
    }

    /**
     * Creates a new empty cell
     *
     * @return MazeCell
     */
    public static function newEmptyCell() : MazeCell
    {
        return new MazeCell(self::CELL_EMPTY);
    }

    /**
     * Creates a new wall cell
     *
     * @return MazeCell
     */
    public static function newWallCell() : MazeCell
    {
        return new MazeCell(self::randomWallContent());
    }

    /**
     * @return int
     */
    public function getContent() : int
    {
        return $this->content;
    }

    /**
     * @param int $content
     * @return MazeCell
     */
    public function setContent(int $content) : MazeCell
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set the cell content to empty
     *
     * @return $this
     */
    public function setEmpty() : MazeCell
    {
        $this->setContent(self::CELL_EMPTY);
        return $this;
    }

    /**
     * Set the cell content to wall
     *
     * @return $this
     */
    public function setWall() : MazeCell
    {
        $this->setContent(self::randomWallContent());
        return $this;
    }

    /**
     * @return bool true if the cell is empty
     */
    public function isEmpty() : bool
    {
        return self::CELL_EMPTY == $this->content;
    }

    /**
     * @return bool true if the cell is a wall
     */
    public function isWall() : bool
    {
        return self::CELL_WALL_BASE & $this->content;
    }

    /**
     * @return int the index of the wall
     */
    public function getWallIndex() : int
    {
        return (~self::CELL_WALL_BASE) & $this->content;
    }

    /**
     * @return int get a random wall
     */
    protected static function randomWallContent() : int
    {
        try {
            return self::CELL_WALL_BASE | random_int(0, self::MAX_RANDOM_WALLS - 1);
        } catch (\Exception $exc) {
            return self::CELL_WALL_BASE;
        }
    }
}
