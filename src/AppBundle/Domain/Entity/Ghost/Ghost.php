<?php

namespace AppBundle\Domain\Entity\Ghost;

use AppBundle\Domain\Entity\Maze\MazeObject;
use AppBundle\Domain\Entity\Position\Position;

/**
 * Class Ghost
 *
 * @package AppBundle\Domain\Entity\Ghost
 */
class Ghost extends MazeObject
{
    /** @var int Ghost types */
    const TYPE_RANDOM = 1;
    const TYPE_KILLING = 2;
    const TYPE_KILLED = 8;

    /** @var int Default values */
    const DEFAULT_NEUTRAL_TIME = 7;
    const DEFAULT_KILLED_TIME = 4;

    /** @var int the type of ghost */
    protected $type;

    /** @var int the number of moves whiles it's neutral or killed */
    protected $timer;

    /** @var int The display version of the ghost */
    protected $display;

    /** @var int The next display - to allow displaying different ghost characters */
    private static $nextDisplay = 0;
    private const MAX_DISPLAY = 4;

    /**
     * Ghost constructor.
     *
     * @param Position      $position
     * @param Position|null $previous
     * @param int|null      $type
     * @param int|null      $timer
     * @param int|null      $display
     */
    public function __construct(
        Position $position,
        Position $previous = null,
        int $type = null,
        int $timer = null,
        int $display = null
    ) {
        parent::__construct($position, $previous);
        $this->type = $type ?? self::TYPE_RANDOM;
        $this->timer = $timer ?? self::DEFAULT_NEUTRAL_TIME;
        $this->display = $display ?? (1 + ((self::$nextDisplay++) % self::MAX_DISPLAY));
    }

    /**
     * Get type of ghost: neutral, random or killing
     *
     * @return int
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Get if the ghost is neutral
     *
     * @return bool
     */
    public function isNeutral()
    {
        return $this->type != self::TYPE_KILLED
            && $this->timer > 0;
    }

    /**
     * Get the display version of the ghost
     *
     * @return int
     */
    public function display(): int
    {
        return $this->display;
    }

    /**
     * Set new ghost type
     *
     * @param $newType
     */
    public function changeType($newType)
    {
        $this->type = $newType;
    }

    /**
     * Moves the ghost
     *
     * @param Position $position
     * @return $this
     */
    public function move(Position $position) : MazeObject
    {
        parent::move($position);
        if ($this->timer > 0) {
            $this->timer--;
        }
        return $this;
    }

    /**
     * The ghost has been killed
     *
     * @param int $timer
     * @return $this|MazeObject
     */
    public function kill($timer = self::DEFAULT_KILLED_TIME) : MazeObject
    {
        $this->type = self::TYPE_KILLED;
        $this->timer = $timer;
        return $this;
    }

    /**
     * Return if the ghost has been killed
     *
     * @return bool
     */
    public function isKilled() : bool
    {
        return $this->type == self::TYPE_KILLED;
    }

    /**
     * Returns if the ghost still killed this move. Decreases the timer every call.
     *
     * @return bool
     */
    public function stillKilled() : bool
    {
        return (--$this->timer > 0);
    }

    /**
     * Serialize the object into an array
     *
     * @return array
     */
    public function serialize()
    {
        return array(
            'position' => $this->position()->serialize(),
            'previous' => $this->previous()->serialize(),
            'type' => $this->type(),
            'timer' => $this->timer,
            'display' => $this->display()
        );
    }

    /**
     * Unserialize from an array and create the object
     *
     * @param array $data
     * @return Ghost
     */
    public static function unserialize(array $data)
    {
        $previous = $data['previous'] ?? null;

        return new static(
            Position::unserialize($data['position']),
            $previous ? Position::unserialize($previous) : null,
            $data['type'] ?? null,
            $data['timer'] ?? null,
            $data['display'] ?? null
        );
    }
}
