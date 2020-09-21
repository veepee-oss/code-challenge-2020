<?php

namespace AppBundle\Form\RegisterCompetitor;

use AppBundle\Domain\Entity\Contest\Competitor;
use AppBundle\Entity\Contest;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form entity: CompetitorEntity
 *
 * @package AppBundle\Form\RegisterCompetitor
 */
class CompetitorEntity
{
    /**
     * @var Contest
     * @Assert\NotBlank(groups={"default", "admin"})
     */
    private $contest = null;

    /**
     * @var string
     * @Assert\NotBlank(groups={"default", "admin"})
     * @Assert\Email(groups={"default", "admin"})
     */
    private $email = null;

    /**
     * @var string
     * @Assert\NotBlank(groups={"admin"})
     */
    private $name = null;

    /**
     * @var string
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Url(groups={"default", "admin"})
     */
    private $url = null;

    /**
     * @var boolean
     */
    private $validated = false;

    /**
     * The URL can't be empty in both the domain entity and the data model.
     */
    private const NULL_URL = "/dev/null";

    /**
     * Converts the entity to a domain entity
     *
     * @return Competitor
     * @throws \Exception
     */
    public function toDomainEntity(): Competitor
    {
        $domainUrl = $this->url ?? self::NULL_URL;
        return new Competitor(
            null,
            $this->contest->getUuid(),
            $this->email,
            $this->name,
            $domainUrl,
            $this->validated,
            null
        );
    }

    /**
     * Loads the competitor form entity from a competitor domain entity.
     *
     * @param Competitor $competitor
     * @return $this
     */
    public function fromDomainEntity(Competitor  $competitor) : CompetitorEntity
    {
        $formUrl = $competitor->url();
        if ($formUrl == self::NULL_URL) {
            $formUrl = null;
        }

        $this->contest = $competitor->contest();
        $this->email = $competitor->email();
        $this->name = $competitor->name();
        $this->url = $formUrl;
        $this->validated = $competitor->validated();
        return $this;
    }

    /**
     * @return Contest|null
     */
    public function getContest(): ?Contest
    {
        return $this->contest;
    }

    /**
     * @param Contest|null $contest
     * @return CompetitorEntity
     */
    public function setContest(?Contest $contest): CompetitorEntity
    {
        $this->contest = $contest;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return CompetitorEntity
     */
    public function setEmail(?string $email): CompetitorEntity
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CompetitorEntity
     */
    public function setName(string $name): CompetitorEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return CompetitorEntity
     */
    public function setUrl(?string $url): CompetitorEntity
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     * @return CompetitorEntity
     */
    public function setValidated(bool $validated): CompetitorEntity
    {
        $this->validated = $validated;
        return $this;
    }
}
