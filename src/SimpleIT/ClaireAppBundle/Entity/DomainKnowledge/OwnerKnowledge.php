<?php

namespace SimpleIT\ExerciseBundle\Entity\DomainKnowledge;

use Doctrine\Common\Collections\Collection;
use SimpleIT\CommonBundle\Entity\User;

/**
 * Claire owner knowledge entity
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledge
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Knowledge
     */
    private $knowledge;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var bool
     */
    private $public;

    /**
     * @var Collection
     */
    private $metadata;

    /**
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set metadata
     *
     * @param Collection $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get metadata
     *
     * @return Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Add a metadata
     *
     * @param Metadata $metadata Metadata
     */
    public function addMetadata($metadata)
    {
        $this->metadata->add($metadata);
    }

    /**
     * Remove a metadata
     *
     * @param Metadata $metadata Metadata
     */
    public function removeMetadata($metadata)
    {
        $this->metadata->removeElement($metadata);
    }

    /**
     * Set owner
     *
     * @param User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set public
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set knowledge
     *
     * @param Knowledge $knowledge
     */
    public function setKnowledge($knowledge)
    {
        $this->knowledge = $knowledge;
    }

    /**
     * Get knowledge
     *
     * @return Knowledge
     */
    public function getKnowledge()
    {
        return $this->knowledge;
    }
}