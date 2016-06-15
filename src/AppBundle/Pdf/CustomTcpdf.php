<?php

namespace AppBundle\Pdf;

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
    private $tha;

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
     */
    public function __construct(AssetsHelper $tha)
    {
        parent::__construct();
        $this->tha = $tha;
    }

    /**
     * Page header
     */
    public function Header()
    {
        // logo
        $this->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape_lowres.jpg'), self::PDF_MARGIN_LEFT, 7);
        $this->SetXY(self::PDF_MARGIN_LEFT, 11);
        $this->setFontStyle(null, 'I', 10);
        $this->setBlueLine();
        $this->Cell(0, 0, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 'B', 0, 'R');
    }

    /**
     * Page footer
     */
    public function Footer()
    {
//        $this->SetFont('dejavusans', 'I', 8);
//        $this->SetY($this->getFooterMargin());
//        $text = $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages();
//        $textWidth = $this->GetStringWidth($text);
//        $x = (((210 - $this->getMargins()['right']) - $this->getMargins()['left']) / 2) + $this->getMargins()['left']  - ($textWidth / 4);
//        $this->Text($x, $this->getFooterMargin(), $text);
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
