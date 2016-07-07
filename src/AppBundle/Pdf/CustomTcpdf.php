<?php

namespace AppBundle\Pdf;

use AppBundle\Entity\Audit;
use AppBundle\Entity\BladeDamage;
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
     * @var float
     */
    public $availablePageWithDimension;

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
        $this->Cell(0, 0, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 'T', 0, 'L');
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
    
    public function setBackgroundHexColor($hex)
    {
        $rgb = $this->hex2rgb($hex);
        $this->SetFillColor($rgb[0], $rgb[1], $rgb[2]);
    }

    public function setBlueLine()
    {
        $this->SetDrawColor($this->colorBlueLight['red'], $this->colorBlueLight['green'], $this->colorBlueLight['blue']);
    }

    public function setBlackLine()
    {
        $this->SetDrawColor(0, 0, 0);
    }
    
    public function drawDamageTableHeader()
    {
        // Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
        $this->setBlueBackground();
        $this->setFontStyle(null, 'B', 9);
        $this->Cell(16, 0, 'DAÑO', 1, 0, 'C', true);
        $this->Cell(35, 0, 'LOCALIZACIÓN', 1, 1, 'C', true);
        $this->setFontStyle(null, '', 9);
        $this->Cell(7, 0, 'Nº', 1, 0, 'C', true);
        $this->Cell(9, 0, 'Cód.', 1, 0, 'C', true);
        $this->Cell(8, 0, 'Pos.', 1, 0, 'C', true);
        $this->Cell(10, 0, 'Radio', 1, 0, 'C', true);
        $this->Cell(17, 0, 'Dist.', 1, 0, 'C', true);
        $this->SetXY($this::PDF_MARGIN_LEFT + 51, $this->GetY() - 6);
        $this->setFontStyle(null, 'B', 9);
        $this->Cell(16, 12, 'TAMAÑO', 1, 0, 'C', true);
        $this->Cell(88, 12, 'DESCRIPCIÓN', 1, 0, 'C', true);
        $this->Cell(0, 12, 'CAT', 1, 1, 'C', true);
        $this->setFontStyle(null, '', 9);
        $this->setWhiteBackground();
    }

    public function drawDamageTableBodyRow(BladeDamage $bladeDamage)
    {
        $this->Cell(7, 0, $bladeDamage->getNumber(), 1, 0, 'C', true);
        $this->Cell(9, 0, $bladeDamage->getDamage()->getCode(), 1, 0, 'C', true);
        $this->Cell(8, 0, $bladeDamage->getPositionString(), 1, 0, 'C', true);
        $this->Cell(10, 0, $bladeDamage->getRadius() . 'm', 1, 0, 'C', true);
        $this->Cell(17, 0, $bladeDamage->getDistanceString(), 1, 0, 'C', true);
        $this->Cell(16, 0, $bladeDamage->getSize() . 'cm', 1, 0, 'C', true);
        $this->Cell(88, 0, $bladeDamage->getDamage()->getDescription(), 1, 0, 'L', true);
        $this->setBackgroundHexColor($bladeDamage->getDamageCategory()->getColour());
        $this->Cell(0, 0, $bladeDamage->getDamageCategory()->getCategory(), 1, 1, 'C', true);
        $this->setWhiteBackground();
    }

    /**
     * @param string $hex
     *
     * @return array
     */
    private function hex2rgb($hex)
    {
        $hex = str_replace('#', '', $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        return array($r, $g, $b);
    }

    public function setAvailablePageDimension()
    {
        $this->availablePageWithDimension = $this->getPageWidth() - self::PDF_MARGIN_LEFT - self::PDF_MARGIN_RIGHT;
    }
}
