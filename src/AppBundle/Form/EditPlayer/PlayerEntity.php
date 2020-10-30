<?php

namespace AppBundle\Form\EditPlayer;

use AppBundle\Domain\Entity\Player\Player;
use AppBundle\Domain\Entity\Position\Position;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form entity: PlayerEntity
 *
 * @package AppBundle\Form\EditPlayer
 */
class PlayerEntity
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email ()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private $url;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $positionY;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $positionX;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $previousY;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $previousX;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $statusCount;

    /**
     * PlayerEntity constructor.
     *
     * @param Player $player
     */
    public function __construct(Player $player)
    {
        $this->name = $player->name();
        $this->email = $player->email();
        $this->url = $player->url();
        $this->positionY = $player->position()->y();
        $this->positionX = $player->position()->x();
        $this->previousY = $player->previous()->y();
        $this->previousX = $player->previous()->x();
        $this->status = $player->status();
        $this->statusCount = $player->statusCount();
    }

    /**
     * Converts the entity to a domain entity
     *
     * @param Player $source
     * @return Player
     */
    public function toDomainEntity(Player $source) : Player
    {
        return $source
            ->setUrl($this->url)
            ->setPlayerIds($this->name, $this->email)
            ->setPlayerConditions(
                new Position($this->positionY, $this->positionX),
                new Position($this->previousY, $this->previousX),
                $this->status,
                $this->statusCount
            );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PlayerEntity
     */
    public function setName(string $name): PlayerEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return PlayerEntity
     */
    public function setEmail(string $email): PlayerEntity
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getPositionY(): int
    {
        return $this->positionY;
    }

    /**
     * @param int $positionY
     * @return PlayerEntity
     */
    public function setPositionY(int $positionY): PlayerEntity
    {
        $this->positionY = $positionY;
        return $this;
    }

    /**
     * @return int
     */
    public function getPositionX(): int
    {
        return $this->positionX;
    }

    /**
     * @param int $positionX
     * @return PlayerEntity
     */
    public function setPositionX(int $positionX): PlayerEntity
    {
        $this->positionX = $positionX;
        return $this;
    }

    /**
     * @return int
     */
    public function getPreviousY(): int
    {
        return $this->previousY;
    }

    /**
     * @param int $previousY
     * @return PlayerEntity
     */
    public function setPreviousY(int $previousY): PlayerEntity
    {
        $this->previousY = $previousY;
        return $this;
    }

    /**
     * @return int
     */
    public function getPreviousX(): int
    {
        return $this->previousX;
    }

    /**
     * @param int $previousX
     * @return PlayerEntity
     */
    public function setPreviousX(int $previousX): PlayerEntity
    {
        $this->previousX = $previousX;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return PlayerEntity
     */
    public function setStatus(int $status): PlayerEntity
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCount(): int
    {
        return $this->statusCount;
    }

    /**
     * @param int $statusCount
     * @return PlayerEntity
     */
    public function setStatusCount(int $statusCount): PlayerEntity
    {
        $this->statusCount = $statusCount;
        return $this;
    }
}
