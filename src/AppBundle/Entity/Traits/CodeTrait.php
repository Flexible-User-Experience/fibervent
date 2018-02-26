<?php

namespace AppBundle\Entity\Traits;

/**
 * Code trait.
 *
 * @category Trait
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
trait CodeTrait
{
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
