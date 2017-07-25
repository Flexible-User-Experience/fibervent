<?php

namespace AppBundle\Pdf;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * Class CustomTcpdf.
 *
 * @category PdfGenerator
 *
 * @author   David Romaní <david@flux.cat>
 */
class CustomTcpdf extends \TCPDF
{
    const PDF_MARGIN_LEFT = 25;
    const PDF_MARGIN_RIGHT = 20;
    const PDF_MARGIN_TOP = 25;
    const PDF_MARGIN_BOTTOM = 10;

    private $colorBlueLight = array('red' => 143, 'green' => 171, 'blue' => 217);
    private $colorBlue = array('red' => 50,  'green' => 118, 'blue' => 179);
    private $colorBlueDark = array('red' => 217, 'green' => 226, 'blue' => 242);

    /**
     * @var AssetsHelper
     */
    protected $tha;

    /**
     * @var Audit
     */
    protected $audit;

    /**
     * @var Translator
     */
    private $ts;

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
     * Methods.
     */

    /**
     * CustomTcpdf constructor.
     *
     * @param AssetsHelper $tha
     * @param Audit        $audit
     * @param Translator   $ts
     */
    public function __construct(AssetsHelper $tha, Audit $audit, Translator $ts)
    {
        parent::__construct();
        $this->tha = $tha;
        $this->audit = $audit;
        $this->ts = $ts;
        $this->windmill = $audit->getWindmill();
        $this->windfarm = $audit->getWindmill()->getWindfarm();
        $this->customer = $audit->getWindmill()->getWindfarm()->getCustomer();
    }

    /**
     * Page header.
     */
    public function header()
    {
        // logo
        $this->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape_hires.jpg'), self::PDF_MARGIN_LEFT, 7, 28);
        $this->SetXY(self::PDF_MARGIN_LEFT, 11);
        $this->setFontStyle(null, 'I', 8);
        $this->setBlueLine();
        $this->Cell(0, 0, $this->ts->trans('pdf.header.1_description', array('%name%' => $this->windfarm->getName())), 'B', 0, 'R');
    }

    /**
     * Page footer.
     */
    public function footer()
    {
        $this->SetXY(self::PDF_MARGIN_LEFT, 280);
        $this->setFontStyle(null, 'I', 8);
        $this->setBlueLine();
        $this->Cell(0, 0, $this->ts->trans('pdf.footer.1_page').' '.$this->getAliasNumPage().' '.$this->ts->trans('pdf.footer.2_of').' '.$this->getAliasNbPages(), 'T', 0, 'C');
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

    /**
     * Set blue text color.
     */
    public function setBlueText()
    {
        $this->SetTextColor($this->colorBlue['red'], $this->colorBlue['green'], $this->colorBlue['blue']);
    }

    /**
     * Set black text color.
     */
    public function setBlackText()
    {
        $this->SetTextColor(0, 0, 0);
    }

    /**
     * Set blue background color.
     */
    public function setBlueBackground()
    {
        $this->SetFillColor($this->colorBlueDark['red'], $this->colorBlueDark['green'], $this->colorBlueDark['blue']);
    }

    /**
     * Set white background color.
     */
    public function setWhiteBackground()
    {
        $this->SetFillColor(255, 255, 255);
    }

    /**
     * Set background color.
     *
     * @param string $hex
     */
    public function setBackgroundHexColor($hex)
    {
        $rgb = $this->hex2rgb($hex);
        $this->SetFillColor($rgb[0], $rgb[1], $rgb[2]);
    }

    /**
     * Set blue line color.
     */
    public function setBlueLine()
    {
        $this->SetDrawColor($this->colorBlueLight['red'], $this->colorBlueLight['green'], $this->colorBlueLight['blue']);
    }

    /**
     * Set blue line color.
     */
    public function setBlackLine()
    {
        $this->SetDrawColor(0, 0, 0);
    }

    /**
     * Set available page dimensions.
     */
    public function setAvailablePageDimension()
    {
        $this->availablePageWithDimension = $this->getPageWidth() - self::PDF_MARGIN_LEFT - self::PDF_MARGIN_RIGHT;
    }

    /**
     * @param string $hex
     *
     * @return array
     */
    private function hex2rgb($hex)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return array($r, $g, $b);
    }
}
