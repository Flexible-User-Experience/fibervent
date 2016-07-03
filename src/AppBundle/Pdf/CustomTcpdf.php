<?php

namespace AppBundle\Pdf;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * Class CustomTcpdf
 *
 * @category PdfGenerator
 * @package  AppBundle\Pdf
 * @author   David Romaní <david@flux.cat>
 */
class CustomTcpdf extends \TCPDF
{
    const PDF_MARGIN_LEFT   = 25;
    const PDF_MARGIN_RIGHT  = 20;
    const PDF_MARGIN_TOP    = 30;
    const PDF_MARGIN_BOTTOM = 10;
    
    private $colorBlueLight = array('red' => 143, 'green' => 171, 'blue' => 217);
    private $colorBlue      = array('red' => 50,  'green' => 118, 'blue' => 179);
    private $colorBlueDark  = array('red' => 217, 'green' => 226, 'blue' => 242);

    /**
     * @var AssetsHelper $tha
     */
    protected $tha;

    /**
     * @var Audit
     */
    protected $audit;

    /**
     * @var Windmill
     */
    protected $windmill;

    /**
     * @var Windfarm
     */
    protected $windfarm;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * 
     * 
     * Methods
     * 
     * 
     */

    /**
     * CustomTcpdf constructor
     *
     * @param AssetsHelper $tha
     * @param Audit        $audit
     */
    public function __construct(AssetsHelper $tha, Audit $audit)
    {
        parent::__construct();
        $this->tha      = $tha;
        $this->audit    = $audit;
        $this->windmill = $audit->getWindmill();
        $this->windfarm = $audit->getWindmill()->getWindfarm();
        $this->customer = $audit->getWindmill()->getWindfarm()->getCustomer();
    }

    /**
     * Page header
     */
    public function Header()
    {
        // logo
        $this->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape_lowres.jpg'), self::PDF_MARGIN_LEFT, 7);
        $this->SetXY(self::PDF_MARGIN_LEFT, 11);
        $this->setFontStyle(null, 'I', 8);
        $this->setBlueLine();
        $this->Cell(0, 0, 'Parque Eólico ' . $this->windfarm->getName(), 'B', 0, 'R');
    }

    /**
     * Page footer
     */
    public function Footer()
    {
        $this->SetXY(self::PDF_MARGIN_LEFT, 280);
        $this->setFontStyle(null, 'I', 8);
        $this->setBlueLine();
        $this->Cell(10, 0, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 'T', 0, 'L');
        $this->Cell(0, 0, 'Ref: #' . $this->audit->getId(), 'T', 0, 'R');
    }

    /**
     * @param string $font
     * @param string $style
     * @param int    $size
     */
    public function setFontStyle($font = 'dejavusans', $style = '', $size = 12)
    {
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $this->SetFont($font, $style, $size, '', true);
    }

    public function setBlueText()
    {
        $this->SetTextColor($this->colorBlue['red'], $this->colorBlue['green'], $this->colorBlue['blue']);
    }

    public function setBlackText()
    {
        $this->SetTextColor(0, 0, 0);
    }

    public function setBlueBackground()
    {
        $this->SetFillColor($this->colorBlueDark['red'], $this->colorBlueDark['green'], $this->colorBlueDark['blue']);
    }

    public function setWhiteBackground()
    {
        $this->SetFillColor(255, 255, 255);
    }

    public function setBlueLine()
    {
        $this->SetDrawColor($this->colorBlueLight['red'], $this->colorBlueLight['green'], $this->colorBlueLight['blue']);
    }

    public function setBlackLine()
    {
        $this->SetDrawColor(0, 0, 0);
    }
}
