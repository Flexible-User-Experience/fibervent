<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * Class abstract base test
 *
 * @category Test
 * @package  AppBundle\Tests
 * @author   David Romaní <david@flux.cat>
 */
class AuditPdfBuilderService
{
    const PDF_MARGIN_LEFT   = 25;
    const PDF_MARGIN_RIGHT  = 20;
    const PDF_MARGIN_TOP    = 20;
    const PDF_MARGIN_BOTTOM = 10;

    /**
     * @var TCPDFController
     */
    private $tcpdf;

    /**
     * @var CacheManager $cm
     */
    private $cm;

    /**
     * @var UploaderHelper $uh
     */
    private $uh;

    /**
     * @var AssetsHelper $tha
     */
    private $tha;

    /**
     * @var string $krd Kernel Root Dir
     */
    private $krd;

    /** @var array */
    private $colorBlue;

    /**
     * AuditPdfBuilderService constructor
     *
     * @param TCPDFController $tcpdf
     * @param CacheManager    $cm
     * @param UploaderHelper  $uh
     * @param AssetsHelper    $tha
     * @param string          $krd
     */
    public function __construct(TCPDFController $tcpdf, CacheManager $cm, UploaderHelper $uh, AssetsHelper $tha, $krd)
    {
        $this->tcpdf = $tcpdf;
        $this->cm    = $cm;
        $this->uh    = $uh;
        $this->tha   = $tha;
        $this->krd   = $krd;
        $this->colorBlueLight = array('red' => 143, 'green' => 171, 'blue' => 217);
        $this->colorBlue      = array('red' => 50,  'green' => 118, 'blue' => 179);
        $this->colorBlueDark  = array('red' => 217, 'green' => 226, 'blue' => 242);
    }

    /**
     * @param Audit $audit
     *
     * @return \TCPDF
     */
    public function build(Audit $audit)
    {
        $windmill = $audit->getWindmill();
        $windfarm = $windmill->getWindfarm();
        $customer = $windfarm->getCustomer();
        $pdf = $this->doInitialConfig($audit, $windmill, $windfarm, $customer);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196, 196, 196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        $pdf->Write(0, 'HitMe!', '', 0, 'C', true, 0, false, false, 0);

        return $pdf;
    }

    /**
     * @param Audit    $audit
     * @param Windmill $windmill
     * @param Windfarm $windfarm
     * @param Customer $customer
     *
     * @return \TCPDF
     */
    private function doInitialConfig(Audit $audit, Windmill $windmill, Windfarm $windfarm, Customer $customer)
    {
        $pdf = $this->tcpdf->create();

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Fibervent');
        $pdf->SetTitle('Auditoria #' . $audit->getId() . ' ' . $windfarm->getName());
        $pdf->SetSubject('Inspección palas parque eólico ' . $windfarm->getName());

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(self::PDF_MARGIN_LEFT, self::PDF_MARGIN_TOP, self::PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, self::PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Set font
        $this->setFont($pdf);

        // Add start page
        $pdf->startPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT);

        // logo
        $pdf->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape.jpg'), 30, 45);

        // main detail section
        $pdf->SetXY(self::PDF_MARGIN_LEFT, 100);
        $this->setBlackText($pdf);
        $this->setBlueBackground($pdf);
        $pdf->Cell(0, 0, 'INSPECCIÓN DE PALAS DEL PARQUE EÓLICO ' . $windfarm->getName(), 'TB', 1, 'C', true);
        $this->setWhiteBackground($pdf);
        $pdf->Cell(0, 0, 'INFORME INDIVIDUAL AEROGENERADOR ' . $windmill->getCode(), 'TB', 1, 'C', true);


        $pdf->Write(0, $windfarm->getPdfLocationString() . '', '', 0, 'C', true, 0, false, false, 0);

        // table detail section
        $pdf->Write(0, '------', '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, 'PAÍS/REGION: ' . $windfarm->getState()->getCountryName() . ' (' . $windfarm->getState()->getName() . ')' . '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'PARQUE EÓLICO: ' . $windfarm->getName(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'MODELO AEROGENERADOR: ' . $windmill->getPdfModelString(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'MODELO PALA: ' . $windmill->getBladeType()->getModel(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'TOTAL No. AG / Capacidad PE: ' . $windfarm->getPdfTotalPowerString(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Puesta en marcha (años del PE): ' . $windfarm->getPdfYearString(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'O&M REGIONAL MANAGER: ' . $windfarm->getManager()->getFullname(), '', 0, 'L', true, 0, false, false, 0);

        // revisions table section
        $pdf->Write(0, '--- REVISIONES ---', '', 0, 'C', true, 0, false, false, 0);

        // operators details
        $pdf->Write(0, '------', '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, 'TÉCNICOS INSPECCIÓN: ' . implode(', ', $audit->getOperators()->getValues()), '', 0, 'L', true, 0, false, false, 0);

        // final details
        $pdf->Write(0, '------', '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, 'TIPO DE INSPECCIÓN: SUELO (Telescopio FIBERVENT)', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'FECHA DE INSPECCIÓN: ' . $audit->getBeginDate()->format('d/m/Y'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'No. de AG / palas inspeccionadas: 1 AG / 3 palas', '', 0, 'L', true, 0, false, false, 0);

        // footer
        $pdf->SetXY(self::PDF_MARGIN_LEFT, 240);
        $this->setFont($pdf, null, null, 8);
        $this->setBlueText($pdf);
        $pdf->Write(0, 'Fibervent, SL', false, false, 'L', true);
        $this->setBlackText($pdf);
        $pdf->Write(0, 'CIF: B55572580', false, false, 'L', true);
        $pdf->Write(0, 'Pol. Industrial Pla de Solans, Parcela 2', false, false, 'L', true);
        $pdf->Write(0, '43519 El Perelló (Tarragona)', false, false, 'L', true);
        $pdf->Write(0, 'Tel. +34 977 490 713', false, false, 'L', true);
        $pdf->Write(0, 'info@fibervent.com', 'mailto:info@fibervent.com', false, 'L', true);
        $pdf->Write(0, 'www.fibervent.com', 'http://www.fibervent.com/', false, 'L');

        return $pdf;
    }

    /**
     * @param \TCPDF $pdf
     * @param string $font
     * @param string $style
     * @param int    $size
     */
    private function setFont(\TCPDF $pdf, $font = 'dejavusans', $style = '', $size = 12)
    {
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont($font, $style, $size, '', true);
    }

    /**
     * @param \TCPDF $pdf
     */
    private function setBlueText(\TCPDF $pdf)
    {
        $pdf->SetTextColor($this->colorBlue['red'], $this->colorBlue['green'], $this->colorBlue['blue']);
    }

    /**
     * @param \TCPDF $pdf
     */
    private function setBlackText(\TCPDF $pdf)
    {
        $pdf->SetTextColor(0, 0, 0);
    }

    /**
     * @param \TCPDF $pdf
     */
    private function setBlueBackground(\TCPDF $pdf)
    {
        $pdf->SetFillColor($this->colorBlueDark['red'], $this->colorBlueDark['green'], $this->colorBlueDark['blue']);
    }

    /**
     * @param \TCPDF $pdf
     */
    private function setWhiteBackground(\TCPDF $pdf)
    {
        $pdf->SetFillColor(255, 255, 255);
    }
}
