<?php

namespace AppBundle\Entity\Traits;

use AppBundle\Entity\State;

/**
 * State trait.
 *
 * @category Trait
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
trait StateTrait
{
    /**
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param State $state
     *
     * @return $this
     */
    public function setState(State $state)
    {
        $this->state = $state;

        return $this;
    }
}
