<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use AppBundle\Pdf\CustomTcpdf;
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
        /** @var CustomTcpdf $pdf */
        $pdf = $this->doInitialConfig($audit, $windmill, $windfarm);

        // remove default header/footer
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);

        // Introduction page
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, CustomTcpdf::PDF_MARGIN_TOP);
        $pdf->setBlackText();
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, '1. INTRODUCCIÓN', '', 0, 'L', true, 0, false, false, 0);
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, 'Este informe es resultado de la inspección visual realizada con telescopio desde suelo realizada en el Parque Eólico ' . $windfarm->getName() . ' entre el ' . $audit->getPdfBeginDateString() . ' y el ' . $audit->getPdfEndDateString(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'El equipo, propiedad de FIBERVENT, y utilizado para la inspección es el siguiente:', '', 0, 'L', true, 0, false, false, 0);
        // TODO items table
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, '2. CATALOGACIÓN DE DAÑOS', '', 0, 'L', true, 0, false, false, 0);
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, 'Los daños encontrados se han categorizado según los siguientes criterios:', '', 0, 'L', true, 0, false, false, 0);
        // TODO damage category table
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, '3. DESCRIPCIÓN DE LA INSPECCIÓN', '', 0, 'L', true, 0, false, false, 0);
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, 'El esquema en la numeración de palas (1, 2, 3) se describe en la siguiente imagen:', '', 0, 'L', true, 0, false, false, 0);
        // TODO windmill schema

        return $pdf;
    }

    /**
     * @param Audit    $audit
     * @param Windmill $windmill
     * @param Windfarm $windfarm
     *
     * @return \TCPDF
     */
    private function doInitialConfig(Audit $audit, Windmill $windmill, Windfarm $windfarm)
    {
        /** @var CustomTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->tha);

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
        $pdf->SetMargins(CustomTcpdf::PDF_MARGIN_LEFT, CustomTcpdf::PDF_MARGIN_TOP, CustomTcpdf::PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, CustomTcpdf::PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add start page
        $pdf->startPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT);

        // logo
        $pdf->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape.jpg'), 30, 45);

        // main detail section
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, 100);
        $pdf->setFontStyle(null, 'B', 14);
        $pdf->setBlackText();
        $pdf->setBlueLine();
        $pdf->setBlueBackground();
        $pdf->Cell(0, 8, 'INSPECCIÓN DE PALAS DEL PARQUE EÓLICO ' . $windfarm->getName(), 'TB', 1, 'C', true);
        $pdf->setWhiteBackground();
        $pdf->Cell(0, 8, 'INFORME INDIVIDUAL AEROGENERADOR ' . $windmill->getCode(), 'TB', 1, 'C', true);
        $pdf->Cell(0, 8, $windfarm->getPdfLocationString(), 'TB', 1, 'C', true);
        $pdf->setFontStyle();

        // table detail section
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'PAÍS/REGION', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getState()->getCountryName() . ' (' . $windfarm->getState()->getName() . ')', 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'PARQUE EÓLICO', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getName(), 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'MODELO AEROGENERADOR', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windmill->getPdfModelString(), 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'MODELO PALA', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windmill->getBladeType()->getModel(), 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'TOTAL No. AG / Capacidad PE', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getPdfTotalPowerString(), 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'Puesta en marcha (años del PE)', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getPdfYearString(), 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'O&M REGIONAL MANAGER', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getManager()->getFullname(), 'TB', 1, 'C', true);

        // TODO revisions table section

        // operators details
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'TÉCNICOS INSPECCIÓN', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, implode(', ', $audit->getOperators()->getValues()), 'TB', 1, 'C', true);

        // final details
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'TIPO DE INSPECCIÓN', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, 'SUELO (Telescopio FIBERVENT)', 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'FECHA DE INSPECCIÓN', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $audit->getPdfBeginDateString(), 'TB', 1, 'C', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'No. de AG / palas inspeccionadas', 'TB', 0, 'C', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, '1 AG / 3 palas', 'TB', 1, 'C', true);

        // footer
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, 250);
        $pdf->setFontStyle(null, null, 8);
        $pdf->setBlueText();
        $pdf->Write(0, 'Fibervent, SL', false, false, 'L', true);
        $pdf->setBlackText();
        $pdf->Write(0, 'CIF: B55572580', false, false, 'L', true);
        $pdf->Write(0, 'Pol. Industrial Pla de Solans, Parcela 2', false, false, 'L', true);
        $pdf->Write(0, '43519 El Perelló (Tarragona)', false, false, 'L', true);
        $pdf->Write(0, 'Tel. +34 977 490 713', false, false, 'L', true);
        $pdf->setFontStyle(null, 'U', 8);
        $pdf->setBlueText();
        $pdf->Write(0, 'info@fibervent.com', 'mailto:info@fibervent.com', false, 'L', true);
        $pdf->setFontStyle(null, null, 8);
        $pdf->Write(0, 'www.fibervent.com', 'http://www.fibervent.com/', false, 'L');
        $pdf->setBlackText();

        return $pdf;
    }
}
