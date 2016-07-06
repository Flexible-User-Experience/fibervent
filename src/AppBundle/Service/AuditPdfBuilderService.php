<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\DamageCategory;
use AppBundle\Entity\Photo;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use AppBundle\Pdf\CustomTcpdf;
use AppBundle\Repository\DamageCategoryRepository;
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
     * @var DamageCategoryRepository
     */
    private $dcr;

    /**
     * @var string $krd Kernel Root Dir
     */
    private $krd;

    /**
     * AuditPdfBuilderService constructor
     *
     * @param TCPDFController          $tcpdf
     * @param CacheManager             $cm
     * @param UploaderHelper           $uh
     * @param AssetsHelper             $tha
     * @param DamageCategoryRepository $dcr
     * @param string                   $krd
     */
    public function __construct(TCPDFController $tcpdf, CacheManager $cm, UploaderHelper $uh, AssetsHelper $tha, DamageCategoryRepository $dcr, $krd)
    {
        $this->tcpdf = $tcpdf;
        $this->cm    = $cm;
        $this->uh    = $uh;
        $this->tha   = $tha;
        $this->dcr   = $dcr;
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

        // Add a page
        $pdf->setPrintHeader(true);
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->setPrintFooter(true);

        // Introduction page
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, CustomTcpdf::PDF_MARGIN_TOP);
        $pdf->setBlackText();
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, '1. INTRODUCCIÓN', '', false, 'L', true);
        $pdf->Ln(5);
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, 'Este informe es el resultado de la inspección visual realizada con telescopio desde suelo en el Parque Eólico "' . $windfarm->getName() . '" entre el ' . $audit->getPdfBeginDateString() . ' y el ' . $audit->getPdfEndDateString(). '. El equipo, propiedad de FIBERVENT, y utilizado para la inspección es el siguiente:', '', false, 'L', true);
        $pdf->Ln(5);
        // Introduction table
        $pdf->setCellPaddings(0, 5, 0, 5);
        $pdf->setCellMargins(10, 0, 10, 0);
        $pdf->MultiCell(0, 0, '<ul><li>Kit telescopio SWAROVSKI ATS 80-HD + OCULAR ZOOM 25-50X</li><li>Adaptador foto SWAROVSKI TLS APO</li><li>Kit cámara OLYMPUS EPL 5 16 Mpx + cable disparador</li><li>Batería OLYMPUS BLS-5</li><li>Objetivo OLYMPUS 14-42 mm</li><li>Adaptador OLYMPUS T-MICRO 4/3</li><li>Kit trípode MANFROTTO NAT DOS Carbono</li><li>Funda trípode MANFROTTO BAG 80</li><li>Mochila LOWEPRO TRAVEL 200 AW</li></ul>', 1, 'L', false, 1, '', '', true, 0, true);
        $pdf->setCellPaddings(1, 1, 1, 1);
        $pdf->setCellMargins(0, 0, 0, 0);
        $pdf->Ln(5);
        // Damages categorization
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, '2. CATALOGACIÓN DE DAÑOS', '', false, 'L', true);
        $pdf->Ln(5);
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, 'Los daños encontrados se han categorizado según los siguientes criterios:', '', false, 'L', true);
        $pdf->Ln(5);
        // Damages table
        $pdf->setBlackLine();
        $pdf->setBlueBackground();
        $pdf->setFontStyle(null, 'B', 9);
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        $pdf->MultiCell(20, 0, 'Categoría', 1, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(20, 0, 'Prioridad', 1, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(60, 0, 'Descripción / Hallazgos', 1, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(0, 0, 'Acción recomendada', 1, 'L', 1, 1, '', '', true);
        $pdf->setFontStyle(null, '', 9);
        /** @var DamageCategory $item */
        foreach ($this->dcr->findAllSortedByCategory() as $item) {
            $pdf->setBackgroundHexColor($item->getColour());
            $pdf->MultiCell(20, 14, $item->getCategory(), 1, 'L', 1, 0, '', '', true);
            $pdf->MultiCell(20, 14, $item->getPriority(), 1, 'L', 1, 0, '', '', true);
            $pdf->MultiCell(60, 14, $item->getDescription(), 1, 'L', 1, 0, '', '', true);
            $pdf->MultiCell(0, 14, $item->getRecommendedAction(), 1, 'L', 1, 1, '', '', true);
        }
        $pdf->setBlueLine();
        $pdf->setWhiteBackground();
        $pdf->Ln(5);
        // Inspection description
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, '3. DESCRIPCIÓN DE LA INSPECCIÓN', '', false, 'L', true);
        $pdf->Ln(5);
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, 'El esquema en la numeración de palas (1, 2, 3) se describe en la siguiente imagen:', '', false, 'L', true);
        $pdf->Ln(5);
        // Audit description with windmill image schema
        $pdf->Image($this->tha->getUrl('/bundles/app/images/tubrine_diagrams/' . $audit->getDiagramType() . '.jpg'), CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY(), null, 40);
        $pdf->AddPage();
        // Damages section
        /** @var AuditWindmillBlade $auditWindmillBlade */
        foreach ($audit->getAuditWindmillBlades() as $key => $auditWindmillBlade) {
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, '3.' . ($key + 1) . ' RESUMEN INDIVIDUAL DAÑOS PALA ' . ($key + 1), '', false, 'L', true);
            $pdf->Ln(5);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, 'En la siguiente tabla se describe el resultado de la inspección con la categorización, descripciones, ubicación y links a fotografías de los daños.', '', false, 'L', true);
            $pdf->Ln(5);
            // damage table
            $pdf->drawDamageTableHeader();
            /** @var BladeDamage $bladeDamage */
            foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
                $pdf->drawDamageTableBodyRow($bladeDamage);
            }
            $pdf->Ln(5);
            // blade diagram damage locations
            $x1 = CustomTcpdf::PDF_MARGIN_LEFT;
            $y1 = $pdf->GetY();
            $x2 = 210 - CustomTcpdf::PDF_MARGIN_RIGHT;
            $y2 = $y1 + 78;
            $bladeGap = 40;
            $pdf->Image($this->tha->getUrl('/bundles/app/images/blade_diagrams/blade_blueprint_1.jpg'), $x1, $y1, null, 78);
//            $pdf->Rect($x1, $y1, ($x2 - $x1), ($y2 - $y1));
//            $pdf->Rect($x1 + 3.5, $y1, ($x2 - $x1 - 5), ($y2 - $y1));
//            $pdf->Rect($x1 + 44.5, $y1, ($x2 - $x1), ($y2 - $y1));
            $pdf->Text(($x1 + ($bladeGap * 1) - 9), $y1 + 32, $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ1LengthString());
            $pdf->Text(($x1 + ($bladeGap * 2) - 10), $y1 + 32, $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ2LengthString());
            $pdf->Text(($x1 + ($bladeGap * 3) - 12), $y1 + 32, $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ3LengthString());
            $pdf->Text(($x1 + ($bladeGap * 4) - 10), $y1 + 32, $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ4LengthString());
            // Damage images pages
            $pdf->AddPage();
            /** @var BladeDamage $bladeDamage */
            foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
                $pdf->drawDamageTableHeader();
                $pdf->drawDamageTableBodyRow($bladeDamage);
                $pdf->Ln(5);
                $i = 0;
                /** @var Photo $photo */
                foreach ($bladeDamage->getPhotos() as $photo) {
                    $pdf->Image($this->cm->getBrowserPath($this->uh->asset($photo, 'imageFile'), '480x270'), CustomTcpdf::PDF_MARGIN_LEFT + (($i % 2) * 85), $pdf->GetY(), 80, null);
                    $i++;
                    if ($i % 2 == 0) {
                        $pdf->Ln(50);
                    }
                }
                $pdf->AddPage();
            }
        }

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
        $pdf = $this->tcpdf->create($this->tha, $audit);

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
        $pdf->Cell(70, 6, 'PAÍS/REGION', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getState()->getCountryName() . ' (' . $windfarm->getState()->getName() . ')', 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'PARQUE EÓLICO', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getName(), 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'MODELO AEROGENERADOR', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windmill->getPdfModelString(), 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'MODELO PALA', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windmill->getBladeType()->getModel(), 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'TOTAL No. AG / Capacidad PE', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getPdfTotalPowerString(), 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'Puesta en marcha (años del PE)', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getPdfYearString(), 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'O&M REGIONAL MANAGER', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $windfarm->getMangerFullname(), 'TB', 1, 'L', true);

        // TODO revisions table section

        // operators details
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'TÉCNICOS INSPECCIÓN', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, implode(', ', $audit->getOperators()->getValues()), 'TB', 1, 'L', true);

        // final details
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'TIPO DE INSPECCIÓN', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, 'SUELO (Telescopio FIBERVENT)', 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'FECHA DE INSPECCIÓN', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, $audit->getPdfBeginDateString(), 'TB', 1, 'L', true);
        $pdf->setFontStyle(null, 'B', 10); $pdf->setBlueBackground();
        $pdf->Cell(70, 6, 'No. de AG / palas inspeccionadas', 'TB', 0, 'R', true);
        $pdf->setFontStyle(null, '', 10); $pdf->setWhiteBackground();
        $pdf->Cell(0, 6, '1 AG / 3 palas', 'TB', 1, 'L', true);

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
