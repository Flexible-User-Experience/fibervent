<?php

namespace AppBundle\Service;

use AppBundle\Pdf\CustomTcpdf;

/**
 * Class AuditModelDiagramBridgeService
 *
 * @category Service
 * @package  AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class AuditModelDiagramBridgeService
{
    const PDF_TOTAL_WIDHT = 210;
    const DIAGRAM_HEIGHT  = 55;

    /**
     * @var float
     */
    private $x1;

    /**
     * @var float
     */
    private $x2;

    /**
     * @var float
     */
    private $y1;

    /**
     * @var float
     */
    private $y2;

    /**
     * @var float
     */
    private $xQ1;

    /**
     * @var float
     */
    private $xQ2;

    /**
     * @var float
     */
    private $xQ3;

    /**
     * @var float
     */
    private $xQ4;

    /**
     * @var float
     */
    private $xQ5;

    /**
     * @var float
     */
    private $yQ1;

    /**
     * @var float
     */
    private $yQ2;

    /**
     * @var float
     */
    private $yMiddle;

    /**
     * @var float
     */
    private $yQ3;

    /**
     * @var float
     */
    private $yQ4;

    /**
     * @var float
     */
    private $xScaleGap;

    /**
     * @var float
     */
    private $yScaleGap;

    /**
     * 
     * 
     * Methods
     * 
     * 
     */

    /**
     * AuditModelDiagramBridgeService constructor
     */
    public function __construct()
    {
        $this->x1 = CustomTcpdf::PDF_MARGIN_LEFT;
        $this->x2 = self::PDF_TOTAL_WIDHT - CustomTcpdf::PDF_MARGIN_RIGHT;
        $this->xQ1 = $this->x1 + 0.25;
        $this->xQ5 = $this->x2 - 0.25;
        $this->xScaleGap = $this->xQ5 - $this->xQ1;
        $this->xQ2 = $this->xQ1 + ($this->xScaleGap / 4);
        $this->xQ3 = $this->xQ2 + ($this->xScaleGap / 4);
        $this->xQ4 = $this->xQ3 + ($this->xScaleGap / 4);
    }
    
    /**
     * @param float $y
     *
     * @return $this
     */
    public function setYs($y)
    {
        $this->y1 = $y;
        $this->y2 = $y + self::DIAGRAM_HEIGHT;
        $this->yQ1 = $this->y1 + 7.25;
        $this->yQ2 = $this->yQ1 + 14.75;
        $this->yQ3 = $this->yQ2 + 12;
        $this->yQ4 = $this->yQ3 + 15;
        $this->yMiddle = $this->yQ2 + (($this->yQ3 - $this->yQ2) / 2) + 0.75;
        
        return $this;
    }

    public function getDeltaGap()
    {

    }

    /**
     * Get X1
     *
     * @return float
     */
    public function getX1()
    {
        return $this->x1;
    }

    /**
     * @param float $x1
     *
     * @return $this
     */
    public function setX1($x1)
    {
        $this->x1 = $x1;

        return $this;
    }

    /**
     * Get X2
     *
     * @return float
     */
    public function getX2()
    {
        return $this->x2;
    }

    /**
     * @param float $x2
     *
     * @return $this
     */
    public function setX2($x2)
    {
        $this->x2 = $x2;

        return $this;
    }

    /**
     * Get Y1
     *
     * @return float
     */
    public function getY1()
    {
        return $this->y1;
    }

    /**
     * @param float $y1
     *
     * @return $this
     */
    public function setY1($y1)
    {
        $this->y1 = $y1;

        return $this;
    }

    /**
     * Get Y2
     *
     * @return float
     */
    public function getY2()
    {
        return $this->y2;
    }

    /**
     * @param float $y2
     *
     * @return $this
     */
    public function setY2($y2)
    {
        $this->y2 = $y2;

        return $this;
    }

    /**
     * Get XQ1
     *
     * @return float
     */
    public function getXQ1()
    {
        return $this->xQ1;
    }

    /**
     * @param float $xQ1
     *
     * @return $this
     */
    public function setXQ1($xQ1)
    {
        $this->xQ1 = $xQ1;

        return $this;
    }

    /**
     * Get XQ2
     *
     * @return float
     */
    public function getXQ2()
    {
        return $this->xQ2;
    }

    /**
     * @param float $xQ2
     *
     * @return $this
     */
    public function setXQ2($xQ2)
    {
        $this->xQ2 = $xQ2;

        return $this;
    }

    /**
     * Get XQ3
     *
     * @return float
     */
    public function getXQ3()
    {
        return $this->xQ3;
    }

    /**
     * @param float $xQ3
     *
     * @return $this
     */
    public function setXQ3($xQ3)
    {
        $this->xQ3 = $xQ3;

        return $this;
    }

    /**
     * Get XQ4
     *
     * @return float
     */
    public function getXQ4()
    {
        return $this->xQ4;
    }

    /**
     * @param float $xQ4
     *
     * @return $this
     */
    public function setXQ4($xQ4)
    {
        $this->xQ4 = $xQ4;

        return $this;
    }

    /**
     * Get XQ5
     *
     * @return float
     */
    public function getXQ5()
    {
        return $this->xQ5;
    }

    /**
     * @param float $xQ5
     *
     * @return $this
     */
    public function setXQ5($xQ5)
    {
        $this->xQ5 = $xQ5;

        return $this;
    }

    /**
     * Get YQ1
     *
     * @return float
     */
    public function getYQ1()
    {
        return $this->yQ1;
    }

    /**
     * @param float $yQ1
     *
     * @return $this
     */
    public function setYQ1($yQ1)
    {
        $this->yQ1 = $yQ1;

        return $this;
    }

    /**
     * Get YQ2
     *
     * @return float
     */
    public function getYQ2()
    {
        return $this->yQ2;
    }

    /**
     * @param float $yQ2
     *
     * @return $this
     */
    public function setYQ2($yQ2)
    {
        $this->yQ2 = $yQ2;

        return $this;
    }

    /**
     * Get YMiddle
     *
     * @return float
     */
    public function getYMiddle()
    {
        return $this->yMiddle;
    }

    /**
     * @param float $yMiddle
     *
     * @return $this
     */
    public function setYMiddle($yMiddle)
    {
        $this->yMiddle = $yMiddle;

        return $this;
    }

    /**
     * Get YQ3
     *
     * @return float
     */
    public function getYQ3()
    {
        return $this->yQ3;
    }

    /**
     * @param float $yQ3
     *
     * @return $this
     */
    public function setYQ3($yQ3)
    {
        $this->yQ3 = $yQ3;

        return $this;
    }

    /**
     * Get YQ4
     *
     * @return float
     */
    public function getYQ4()
    {
        return $this->yQ4;
    }

    /**
     * @param float $yQ4
     *
     * @return $this
     */
    public function setYQ4($yQ4)
    {
        $this->yQ4 = $yQ4;

        return $this;
    }

    /**
     * Get XScaleGap
     *
     * @return float
     */
    public function getXScaleGap()
    {
        return $this->xScaleGap;
    }

    /**
     * Set XScaleGap
     *
     * @param float $xScaleGap
     *
     * @return $this
     */
    public function setXScaleGap($xScaleGap)
    {
        $this->xScaleGap = $xScaleGap;

        return $this;
    }

    /**
     * Get YScaleGap
     *
     * @return float
     */
    public function getYScaleGap()
    {
        return $this->yScaleGap;
    }

    /**
     * Set YScaleGap
     *
     * @param float $yScaleGap
     *
     * @return $this
     */
    public function setYScaleGap($yScaleGap)
    {
        $this->yScaleGap = $yScaleGap;

        return $this;
    }
}
