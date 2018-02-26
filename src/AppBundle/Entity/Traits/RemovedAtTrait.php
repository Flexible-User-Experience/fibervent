<?php

namespace AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * RemovedAtTrait
 *
 * @category Trait
 * @author   David Romaní <david@flux.cat>
 */
Trait RemovedAtTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $removedAt;

    /**
     * Set removedAt
     *
     * @param \DateTime $removedAt
     *
     * @return $this
     */
    public function setRemovedAt(\DateTime $removedAt)
    {
        $this->removedAt = $removedAt;

        return $this;
    }

    /**
     * Get removedAt
     *
     * @return \DateTime
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * Is deleted?
     *
     * @return bool
     */
    public function isDeleted()
    {
        return $this->removedAt !== null;
    }
}
