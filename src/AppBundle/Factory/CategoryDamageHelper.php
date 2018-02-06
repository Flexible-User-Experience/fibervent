<?php

namespace AppBundle\Factory;

/**
 * Class CategoryDamageHelper
 *
 * @category FactoryHelper
 *
 * @author   David Romaní <david@flux.cat>
 */
class CategoryDamageHelper
{
    const MARK = 'X';

    /**
     * @var integer
     */
    private $number;

    /**
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $mark;

    /**
     * @var array|string[]
     */
    private $letterMarks;

    /**
     * Methods
     */

    /**
     * CategoryDamageHelper constructor.
     */
    public function __construct()
    {
        $this->mark = '';
        $this->letterMarks = array();
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return $this
     */
    public function setColor(string $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param string $mark
     *
     * @return $this
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return array
     */
    public function getLetterMarks()
    {
        return $this->letterMarks;
    }

    /**
     * @return string
     */
    public function getLetterMarksToString()
    {
        return implode(', ', $this->letterMarks);
    }

    /**
     * @param array|string[] $letterMarks
     *
     * @return $this
     */
    public function setLetterMarks($letterMarks)
    {
        $this->letterMarks = $letterMarks;

        return $this;
    }

    /**
     * @param string $letterMark
     *
     * @return $this
     */
    public function addLetterMark($letterMark)
    {
        $this->letterMarks[] = $letterMark;
        $this->mark = self::MARK;

        return $this;
    }
}
