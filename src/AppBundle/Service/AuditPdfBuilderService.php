<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\BladePhoto;
use AppBundle\Entity\DamageCategory;
use AppBundle\Entity\Observation;
use AppBundle\Entity\Photo;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use AppBundle\Enum\WindfarmLanguageEnum;
use AppBundle\Pdf\CustomTcpdf;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\DamageRepository;
use AppBundle\Repository\BladeDamageRepository;
use AppBundle\Repository\DamageCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * Class Audit Pdf Builder Service.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class AuditPdfBuilderService
{
    const SHOW_GRID_DEBUG   = false;
    const SHOW_ONLY_DIAGRAM = true;

    /**
     * @var TCPDFController
     */
    private $tcpdf;

    /**
     * @var CacheManager
     */
    private $cm;

    /**
     * @var UploaderHelper
     */
    private $uh;

    /**
     * @var AssetsHelper
     */
    private $tha;

    /**
     * @var Translator
     */
    private $ts;

    /**
     * @var DamageRepository
     */
    private $dr;

    /**
     * @var DamageCategoryRepository
     */
    private $dcr;

    /**
     * @var BladeDamageRepository
     */
    private $bdr;

    /**
     * @var CustomerRepository
     */
    private $cr;

    /**
     * @var AuditModelDiagramBridgeService
     */
    private $amdb;

    /**
     * @var string
     */
    private $locale;

    /**
     * Methods.
     */

    /**
     * AuditPdfBuilderService constructor.
     *
     * @param TCPDFController                $tcpdf
     * @param CacheManager                   $cm
     * @param UploaderHelper                 $uh
     * @param AssetsHelper                   $tha
     * @param Translator                     $ts
     * @param DamageRepository               $dr
     * @param DamageCategoryRepository       $dcr
     * @param BladeDamageRepository          $bdr
     * @param CustomerRepository             $cr
     * @param AuditModelDiagramBridgeService $amdb
     */
    public function __construct(TCPDFController $tcpdf, CacheManager $cm, UploaderHelper $uh, AssetsHelper $tha, Translator $ts, DamageRepository $dr, DamageCategoryRepository $dcr, BladeDamageRepository $bdr, CustomerRepository $cr, AuditModelDiagramBridgeService $amdb)
    {
        $this->tcpdf = $tcpdf;
        $this->cm = $cm;
        $this->uh = $uh;
        $this->tha = $tha;
        $this->ts = $ts;
        $this->dr = $dr;
        $this->dcr = $dcr;
        $this->bdr = $bdr;
        $this->cr = $cr;
        $this->amdb = $amdb;
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
        $this->locale = WindfarmLanguageEnum::getEnumArray()[$windfarm->getLanguage()];
        $this->ts->setLocale($this->locale);

        /** @var CustomTcpdf $pdf */
        $pdf = $this->doInitialConfig($audit, $windmill, $windfarm);

        // Add a page
        $pdf->setPrintHeader(true);
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->setAvailablePageDimension();
        $pdf->setPrintFooter(true);

        if (!self::SHOW_ONLY_DIAGRAM) {
            // Introduction page
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, CustomTcpdf::PDF_MARGIN_TOP);
            $pdf->setBlackText();
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf.intro.1_title'), '', false, 'L', true);
            $pdf->Ln(2);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf.intro.2_description', ['%windfarm%' => $windfarm->getName(), '%begin%' => $audit->getPdfBeginDateString(), '%end%' => $audit->getPdfEndDateString()]), '', false, 'L', true);
            $pdf->Ln(2);
            // Introduction table
            $pdf->setCellPaddings(20, 2, 20, 2);
            $pdf->setCellMargins(0, 0, 0, 0);
            $pdf->MultiCell(0, 0, $this->ts->trans('pdf.intro.3_list'), 1, 'L', false, 1, '', '', true, 0, true);
            $pdf->setCellPaddings(1, 1, 1, 1);
            $pdf->setCellMargins(0, 0, 0, 0);
            $pdf->Ln(10);
            // Damages categorization
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf.damage_catalog.1_title'), '', false, 'L', true);
            $pdf->Ln(2);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf.damage_catalog.2_subtitle'), '', false, 'L', true);
            $pdf->Ln(2);
            // Damages table
            $pdf->setBlackLine();
            $pdf->setBlueBackground();
            $pdf->setFontStyle(null, 'B', 9);
            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            $pdf->MultiCell(20, 0, $this->ts->trans('pdf.damage_catalog.table.1_category'), 1, 'C', 1, 0, '', '', true);
            $pdf->MultiCell(20, 0, $this->ts->trans('pdf.damage_catalog.table.2_priority'), 1, 'C', 1, 0, '', '', true);
            $pdf->MultiCell(60, 0, $this->ts->trans('pdf.damage_catalog.table.3_description'), 1, 'C', 1, 0, '', '', true);
            $pdf->MultiCell(0, 0, $this->ts->trans('pdf.damage_catalog.table.4_action'), 1, 'C', 1, 1, '', '', true);
            $pdf->setFontStyle(null, '', 9);
            /** @var DamageCategory $item */
            foreach ($this->dcr->findAllSortedByCategoryLocalized($this->locale) as $item) {
                $pdf->setBackgroundHexColor($item->getColour());
                $pdf->MultiCell(20, 14, $item->getCategory(), 1, 'C', 1, 0, '', '', true, 0, false, true, 14, 'M');
                $pdf->MultiCell(20, 14, $item->getPriority(), 1, 'C', 1, 0, '', '', true, 0, false, true, 14, 'M');
                $pdf->MultiCell(60, 14, $item->getDescription(), 1, 'L', 1, 0, '', '', true, 0, false, true, 14, 'M');
                $pdf->MultiCell(0, 14, $item->getRecommendedAction(), 1, 'L', 1, 1, '', '', true, 0, false, true, 14, 'M');
            }
            $pdf->setBlueLine();
            $pdf->setWhiteBackground();
            $pdf->Ln(10);
            // Inspection description
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf.audit_description.1_title'), '', false, 'L', true);
            $pdf->Ln(2);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf.audit_description.2_description'), '', false, 'L', true);
            $pdf->Ln(2);
            // Audit description with windmill image schema
            $pdf->Image($this->tha->getUrl('/bundles/app/images/tubrine_diagrams/'.$audit->getDiagramType().'.jpg'), CustomTcpdf::PDF_MARGIN_LEFT + 50, $pdf->GetY(), null, 40);
            $pdf->AddPage();
        }
        $pdf->setCellPaddings(1, 1, 1, 1);
        $pdf->setCellMargins(0, 0, 0, 0);

        // Damages section
        /** @var AuditWindmillBlade $auditWindmillBlade */
        foreach ($audit->getAuditWindmillBlades() as $key => $auditWindmillBlade) {
            $bladeDamages = $this->bdr->getItemsOfAuditWindmillBladeSortedByRadius($auditWindmillBlade);
            $pdf->setFontStyle(null, 'B', 11);
            $serialNumberSuffix = $auditWindmillBlade->getWindmillBlade()->getCode() ? ' - (S/N: '.($auditWindmillBlade->getWindmillBlade()->getCode()).')' : '';
            $pdf->Write(0, '3.'.($key + 1).' '.$this->ts->trans('pdf.audit_blade_damage.1_title').' '.($key + 1).$serialNumberSuffix, '', false, 'L', true);
            $pdf->Ln(5);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf.audit_blade_damage.2_description'), '', false, 'L', true);
            $pdf->Ln(5);
            // damage table
            $this->drawDamageTableHeader($pdf);

            /** @var BladeDamage $bladeDamage */
            foreach ($bladeDamages as $sKey => $bladeDamage) {
                $this->drawDamageTableBodyRow($pdf, $sKey, $bladeDamage);
            }
            $pdf->Ln(7);
            $yBreakpoint = $pdf->GetY();

            // blade diagram damage locations
            $this->amdb->setYs($pdf->GetY());
            $x1 = $this->amdb->getX1();
            $y1 = $this->amdb->getY1();
            $x2 = $this->amdb->getX2();
            $y2 = $this->amdb->getY2();

            $xQuarter1 = $this->amdb->getXQ1();
            $xQuarter2 = $this->amdb->getXQ2();
            $xQuarter3 = $this->amdb->getXQ3();
            $xQuarter4 = $this->amdb->getXQ4();
            $xQuarter5 = $this->amdb->getXQ5();

            $yMiddle = $this->amdb->getYMiddle();
            $yMiddle2 = $this->amdb->getYMiddle2();
            $yQuarter1 = $this->amdb->getYQ1();
            $yQuarter2 = $this->amdb->getYQ2();
            $yQuarter3 = $this->amdb->getYQ3();
            $yQuarter4 = $this->amdb->getYQ4();

            // blade diagram middle lines
            $this->amdb->enableDebugLineStyles($pdf, true);
            // verticals
            $pdf->Line($xQuarter1, $y1, $xQuarter1, $y1 + ($y2 - $y1));
            $pdf->Line($xQuarter2, $y1, $xQuarter2, $y1 + ($y2 - $y1));
            $pdf->Line($xQuarter3, $y1, $xQuarter3, $y1 + ($y2 - $y1));
            $pdf->Line($xQuarter4, $y1, $xQuarter4, $y1 + ($y2 - $y1));
            $pdf->Line($xQuarter5, $y1, $xQuarter5, $y1 + ($y2 - $y1));
            // hortizontals
            $pdf->Line($x1, $y1 + 3, $x2, $y1 + 3);
            $pdf->Line($x1, $yMiddle2 + 8, $x2, $yMiddle2 + 8);

            if (self::SHOW_GRID_DEBUG) {
                $pdf->Line($x1, $yQuarter1, $x2, $yQuarter1);
                $pdf->Line($x1, $yQuarter2, $x2, $yQuarter2);
                $pdf->Line($x1, $yMiddle, $x2, $yMiddle);
                $pdf->Line($x1, $yQuarter3, $x2, $yQuarter3);
                $pdf->Line($x1, $yQuarter4, $x2, $yQuarter4);
                $pdf->Line($x1, $yMiddle2, $x2, $yMiddle2);
                $this->amdb->enableDebugLineStyles($pdf, false);
            }

            // blade diagram radius helper
            $txt = $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ0LengthString();
            $pdf->Text(($x1 - $pdf->GetStringWidth($txt) - 2), $y1 - 2, $txt);
            $pdf->Text(($x1 - $pdf->GetStringWidth($txt) - 2), $yMiddle2 + 7, $txt);
            $txt = $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ1LengthString();
            $pdf->Text(($xQuarter2 - $pdf->GetStringWidth($txt) - 2), $y1 - 2, $txt);
            $pdf->Text(($xQuarter2 - $pdf->GetStringWidth($txt) - 2), $yMiddle2 + 7, $txt);
            $txt = $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ2LengthString();
            $pdf->Text(($xQuarter3 - $pdf->GetStringWidth($txt) - 2), $y1 - 2, $txt);
            $pdf->Text(($xQuarter3 - $pdf->GetStringWidth($txt) - 2), $yMiddle2 + 7, $txt);
            $txt = $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ3LengthString();
            $pdf->Text(($xQuarter4 - $pdf->GetStringWidth($txt) - 2), $y1 - 2, $txt);
            $pdf->Text(($xQuarter4 - $pdf->GetStringWidth($txt) - 2), $yMiddle2 + 7, $txt);
            $txt = $auditWindmillBlade->getWindmillBlade()->getWindmill()->getBladeType()->getQ4LengthString();
            $pdf->Text(($xQuarter5 - $pdf->GetStringWidth($txt) - 2), $y1 - 2, $txt);
            $pdf->Text(($xQuarter5 - $pdf->GetStringWidth($txt) - 2), $yMiddle2 + 7, $txt);
            $pdf->setBlackLine();
            $pdf->SetLineStyle(array('dash' => 0));

            $pdf->SetX(CustomTcpdf::PDF_MARGIN_LEFT);
            $pdf->StartTransform();
            $pdf->Rotate(90);
            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            $pdf->MultiCell(18, 0, $this->ts->trans('pdf.blade_damage_diagram.6_bs_l_short'), 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(20, 0, $this->ts->trans('pdf.blade_damage_diagram.2_vs_s'), 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(20, 0, $this->ts->trans('pdf.blade_damage_diagram.5_ba_l_short'), 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(20, 0, $this->ts->trans('pdf.blade_damage_diagram.1_vp_s'), 1, 'C', 0, 0, '', '', true);
            $pdf->StopTransform();

            // Draw blade diagram
            $polyArray = array();
            $polyArray2 = array();
            $xStep = $xQuarter1;
            $xDelta = ($xQuarter5 - $xQuarter1) / 50;
            foreach ($this->amdb->getBladeShape() as $yPoint) {
                $yTransform = $yQuarter2 - (($yQuarter2 - $yQuarter1) * $yPoint);
                $yTransform2 = $yQuarter3 + (($yQuarter4 - $yQuarter3) * $yPoint);
                array_push($polyArray, $xStep);
                array_push($polyArray, $yTransform);
                array_push($polyArray2, $xStep);
                array_push($polyArray2, $yTransform2);
                $xStep += $xDelta;
            }
            array_push($polyArray, $xQuarter5);
            array_push($polyArray, $yQuarter2);
            array_push($polyArray, $xQuarter1);
            array_push($polyArray, $yQuarter2);
            array_push($polyArray2, $xQuarter5);
            array_push($polyArray2, $yQuarter3);
            array_push($polyArray2, $xQuarter1);
            array_push($polyArray2, $yQuarter3);
            $pdf->SetLineStyle(array('dash' => 0, 'width' => 0.35));
            $pdf->Polygon($polyArray);
            $pdf->Polygon($polyArray2);

            // Draw edge blade diagram
            $polyArray = array();
            $polyArray2 = array();
            $xStep = $xQuarter1;
            $xDelta = ($xQuarter5 - $xQuarter1) / 50;
            foreach ($this->amdb->getEdgeBladeShape() as $yPoint) {
                $yTransform = $yMiddle - ((($yQuarter2 - $yQuarter1) / 2) * $yPoint);
                $yTransform2 = $yMiddle2 - ((($yQuarter2 - $yQuarter1) / 2) * $yPoint);
                array_push($polyArray, $xStep);
                array_push($polyArray, $yTransform);
                array_push($polyArray2, $xStep);
                array_push($polyArray2, $yTransform2);
                $xStep += $xDelta;
            }
            $xStep = $xQuarter5;
            foreach (array_reverse($this->amdb->getEdgeBladeShape()) as $yPoint) {
                $yTransform = $yMiddle + ((($yQuarter2 - $yQuarter1) / 2) * $yPoint);
                $yTransform2 = $yMiddle2 + ((($yQuarter2 - $yQuarter1) / 2) * $yPoint);
                array_push($polyArray, $xStep);
                array_push($polyArray, $yTransform);
                array_push($polyArray2, $xStep);
                array_push($polyArray2, $yTransform2);
                $xStep -= $xDelta;
            }
            $pdf->SetLineStyle(array('dash' => 0, 'width' => 0.35));
            $pdf->Polygon($polyArray);
            $pdf->Polygon($polyArray2);
            $pdf->SetLineStyle(array('dash' => 0, 'width' => 0.15));
            $pdf->Line($xQuarter2 - 6, $yMiddle2, $x2, $yMiddle2);

            /** @var BladeDamage $bladeDamage */
            foreach ($bladeDamages as $sKey => $bladeDamage) {
                $this->amdb->drawCenterDamage($pdf, $bladeDamage, $sKey + 1);
                if (self::SHOW_GRID_DEBUG) {
                    $pdf->setRedLine();
                    $this->amdb->drawCenterPoint($pdf, $bladeDamage);
                    $pdf->setBlackLine();
                }
            }

            $pdf->setWhiteBackground();
            $pdf->SetY($yBreakpoint + AuditModelDiagramBridgeService::DIAGRAM_HEIGHT + 10);

            // Observations table
            if (count($auditWindmillBlade->getObservations()) > 0 && !self::SHOW_ONLY_DIAGRAM) {
                $pdf->setBlueLine();
                $pdf->SetLineStyle(array('width' => 0.25));
                $pdf->setBlueBackground();
                $pdf->setFontStyle(null, 'B', 9);
                $pdf->Cell(16, 0, $this->ts->trans('pdf.observations_table.1_damage'), 1, 0, 'C', true);
                $pdf->Cell(0, 0, $this->ts->trans('pdf.observations_table.2_observations'), 1, 1, 'C', true);
                $pdf->setFontStyle(null, '', 9);
                $pdf->setWhiteBackground();
                /** @var Observation $observation */
                foreach ($auditWindmillBlade->getObservations() as $observation) {
                    $h = $pdf->getStringHeight(AuditModelDiagramBridgeService::PDF_TOTAL_WIDHT - CustomTcpdf::PDF_MARGIN_LEFT - CustomTcpdf::PDF_MARGIN_RIGHT - 16, $observation->getObservations());
                    $pdf->MultiCell(0, $h, $observation->getObservations(), 1, 'L', 0, 0, CustomTcpdf::PDF_MARGIN_LEFT + 16, '', true, 0, false, true, 0, 'M');
                    $pdf->MultiCell(16, $h, $observation->getDamageNumber(), 1, 'C', 0, 1, CustomTcpdf::PDF_MARGIN_LEFT, '', true, 0, false, true, 0, 'M');
                }
                $pdf->Ln(5);
            }

            // General blade damage images
            if (count($auditWindmillBlade->getBladePhotos()) > 0 && !self::SHOW_ONLY_DIAGRAM) {
                $pdf->AddPage();
                $pdf->setFontStyle(null, 'B', 11);
                $pdf->Write(0, '3.'.($key + 1).'.'.$this->ts->trans('pdf.blade_damage_images.1_general_blade_views').' '.($key + 1), '', false, 'L', true);
                $pdf->Ln(3);
                $pdf->setFontStyle(null, '', 9);
                $i = 0;
                /** @var BladePhoto $photo */
                foreach ($auditWindmillBlade->getBladePhotos() as $photo) {
                    if ($photo->getImageName()) {
                        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
                        $pdf->Image($this->cm->getBrowserPath($this->uh->asset($photo, 'imageFile'), '600x960'), CustomTcpdf::PDF_MARGIN_LEFT + (($i % 2) * 76) + 7, $pdf->GetY(), null, 115);
                        ++$i;
                        if ($i % 2 == 0) {
                            $pdf->Ln(120);
                        }
                    }
                }
                $pdf->Ln(5);
            }

            $pdf->AddPage();
            if (!self::SHOW_ONLY_DIAGRAM) {
                // Damage images pages
                $pdf->setBlueLine();
                /** @var BladeDamage $bladeDamage */
                foreach ($bladeDamages as $sKey => $bladeDamage) {
                    $this->drawDamageTableHeader($pdf);
                    $this->drawDamageTableBodyRow($pdf, $sKey, $bladeDamage);
                    $pdf->Ln(5);
                    /** @var Photo $photo */
                    foreach ($bladeDamage->getPhotos() as $photo) {
                        if ($photo->getImageName()) {
                            $pdf->Image($this->cm->getBrowserPath($this->uh->asset($photo, 'imageFile'), '960x540'), CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY(), $pdf->availablePageWithDimension, null);
                            $pdf->Ln(100);
                        }
                    }
                    $pdf->AddPage();
                }
            }
        }

        if (!self::SHOW_ONLY_DIAGRAM) {
            // Contact section
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf.inspection_description.1_contact'), '', false, 'L', true);
            $pdf->Ln(5);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf.inspection_description.2_description'), '', false, 'L', true);
            $pdf->Ln(10);
            $pdf->Cell(10, 0, '', 0, 0);
            $pdf->Cell(0, 0, $this->ts->trans('pdf.inspection_description.3_offices'), 0, 1, 'L', 0, '');
            $pdf->Ln(5);
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'Pol. Industrial Pal de Solans, Parcela 2', 0, 1, 'L', 0, '');
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, '43519 El Perelló (Tarragona)', 0, 1, 'L', 0, '');
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'Tel: +34 977 490 713', 0, 1, 'L', 0, '');
            $pdf->setFontStyle(null, 'U', 9);
            $pdf->setBlueText();
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'fibervent@fibervent.com', 0, 1, 'L', 0, 'mailto:fibervent@fibervent.com');
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'www.fibervent.com', 0, 1, 'L', 0, 'www.fibervent.com');
            $pdf->setFontStyle(null, '', 9);
            $pdf->setBlackText();
            $pdf->Ln(10);
            $pdf->Cell(10, 0, '', 0, 0);
            $pdf->Cell(0, 0, $this->ts->trans('pdf.inspection_description.4_phones_emails'), 0, 1, 'L', 0, '');
            $pdf->Ln(5);
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'David Espasa (+34 636 317 884)', 0, 1, 'L', 0, '');
            $pdf->setFontStyle(null, 'U', 9);
            $pdf->setBlueText();
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'info@fibervent.com', 0, 1, 'L', 0, 'mailto:info@fibervent.com');
            $pdf->setFontStyle(null, '', 9);
            $pdf->setBlackText();
            $pdf->Ln(3);
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'Eduard Borràs (+34 636 690 757)', 0, 1, 'L', 0, '');
            $pdf->setFontStyle(null, 'U', 9);
            $pdf->setBlueText();
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'fibervent@fibervent.com', 0, 1, 'L', 0, 'mailto:fibervent@fibervent.com');
            $pdf->setFontStyle(null, '', 9);
            $pdf->setBlackText();
            $pdf->Ln(3);
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'Josep Marsal (+34 647 610 351)', 0, 1, 'L', 0, '');
            $pdf->setFontStyle(null, 'U', 9);
            $pdf->setBlueText();
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'tecnic@fibervent.com', 0, 1, 'L', 0, 'mailto:tecnic@fibervent.com');
            $pdf->setFontStyle(null, '', 9);
            $pdf->setBlackText();
            $pdf->Ln(3);
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'Sergio López (+34 618 277 158)', 0, 1, 'L', 0, '');
            $pdf->setFontStyle(null, 'U', 9);
            $pdf->setBlueText();
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(0, 0, 'oficinatecnica@fibervent.com', 0, 1, 'L', 0, 'mailto:oficinatecnica@fibervent.com');
            $pdf->setFontStyle(null, '', 9);
            $pdf->setBlackText();
            $pdf->Ln(15);
            $pdf->Write(0, $this->ts->trans('pdf.inspection_description.5_gratitude'), '', false, 'L', true);
            $pdf->Ln(5);
            $pdf->Write(0, 'FIBERVENT, S.L.', '', false, 'L', true);
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
        $pdf = $this->tcpdf->create($this->tha, $audit, $this->ts);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Fibervent');
        $pdf->SetTitle($this->ts->trans('pdf.set_document_information.1_title').$audit->getId().' '.$windfarm->getName());
        $pdf->SetSubject($this->ts->trans('pdf.set_document_information.2_subject').$windfarm->getName());
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(CustomTcpdf::PDF_MARGIN_LEFT, CustomTcpdf::PDF_MARGIN_TOP, CustomTcpdf::PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, CustomTcpdf::PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (!self::SHOW_ONLY_DIAGRAM) {
            // add start page
            $pdf->startPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT);
            // logo
            if ($audit->getCustomer()->getImageName()) {
                $pdf->Image($this->uh->asset($audit->getCustomer(), 'imageFile'), 43, 45, 32);
                $pdf->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape_hires.jpg'), 100, 45, 78);
            } else {
                $pdf->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape_hires.jpg'), '', 45, 130, '', 'JPEG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
            }
            // main detail section
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, 100);
            $pdf->setFontStyle(null, 'B', 14);
            $pdf->setBlackText();
            $pdf->setBlueLine();
            $pdf->setBlueBackground();
            $pdf->Cell(0, 8, $this->ts->trans('pdf.cover.1_inspection').' '.$windfarm->getName(), 'TB', 1, 'C', true);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 8, $this->ts->trans('pdf.cover.2_resume').' '.$windmill->getCode(), 'TB', 1, 'C', true);
            $pdf->Cell(0, 8, $windfarm->getPdfLocationString(), 'TB', 1, 'C', true);
            $pdf->setFontStyle();
            // table detail section
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.3_country'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windfarm->getState()->getCountryName().' ('.$windfarm->getState()->getName().')', 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.4_windfarm'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windfarm->getName(), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.5_turbine_model'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windmill->getTurbine()->pdfToString(), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.6_turbine_size'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans('pdf.cover.6_turbine_size_value', ['%height%' => $windmill->getTurbine()->getTowerHeight(), '%diameter%' => $windmill->getTurbine()->getRotorDiameter(), '%length%' => $windmill->getBladeType()->getLength()]), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.7_blade_type'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windmill->getBladeType(), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.8_total_turbines'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans('pdf.cover.8_total_turbines_value', ['%windmills%' => $windfarm->getWindmillAmount(), '%power%' => $windfarm->getPower()]), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.9_startup_year'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans('pdf.cover.9_startup_year_value', ['%year%' => $windfarm->getYear(), '%diff%' => $windfarm->getYearDiff()]), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.10_om_regional_manager'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windfarm->getMangerFullname(), 'TB', 1, 'L', true);
            // operators details
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.11_technicians'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, implode(', ', $audit->getOperators()->getValues()), 'TB', 1, 'L', true);
            // final details
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.12_audit_type'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans($audit->getTypeStringLocalized()), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.13_audit_date'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $audit->getPdfBeginDateString(), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.14_blades_amout'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans('pdf.cover.14_blades_amout_value'), 'TB', 1, 'L', true);
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
        }

        return $pdf;
    }

    /**
     * Draw damage table header.
     *
     * @param CustomTcpdf $pdf
     */
    private function drawDamageTableHeader(CustomTcpdf $pdf)
    {
        $pdf->setBlueBackground();
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(16, 0, $this->ts->trans('pdf.damage_table_header.1_damage'), 1, 0, 'C', true);
        $pdf->Cell(37, 0, $this->ts->trans('pdf.damage_table_header.2_position'), 1, 1, 'C', true);
        $pdf->setFontStyle(null, '', 9);
        $pdf->Cell(7, 0, $this->ts->trans('pdf.damage_table_header.3_number'), 1, 0, 'C', true);
        $pdf->Cell(9, 0, $this->ts->trans('pdf.damage_table_header.4_code'), 1, 0, 'C', true);
        $pdf->Cell(8, 0, 'Pos.', 1, 0, 'C', true);
        $pdf->Cell(12, 0, $this->ts->trans('pdf.damage_table_header.5_radius'), 1, 0, 'C', true);
        $pdf->Cell(17, 0, $this->ts->trans('pdf.damage_table_header.8_distance'), 1, 0, 'C', true);
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT + 53, $pdf->GetY() - 6);
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(16, 12, $this->ts->trans('pdf.damage_table_header.6_size'), 1, 0, 'C', true);
        $pdf->Cell(86, 12, $this->ts->trans('pdf.damage_table_header.7_description'), 1, 0, 'C', true);
        $pdf->Cell(0, 12, 'CAT', 1, 1, 'C', true);
        $pdf->setFontStyle(null, '', 9);
        $pdf->setWhiteBackground();
    }

    /**
     * Draw damage table body row.
     *
     * @param CustomTcpdf $pdf
     * @param int         $key
     * @param BladeDamage $bladeDamage
     */
    private function drawDamageTableBodyRow(CustomTcpdf $pdf, $key, BladeDamage $bladeDamage)
    {
        $pdf->Cell(7, 0, $key + 1, 1, 0, 'C', true);
        $pdf->Cell(9, 0, $bladeDamage->getDamage()->getCode(), 1, 0, 'C', true);
        $pdf->Cell(8, 0, $this->ts->trans($bladeDamage->getPositionStringLocalized()), 1, 0, 'C', true);
        $pdf->Cell(12, 0, $bladeDamage->getRadius().'m', 1, 0, 'C', true);
        $pdf->Cell(17, 0, $this->ts->trans($bladeDamage->getLocalizedDistanceString(), array('%dist%' => $bladeDamage->getDistanceScaled())), 1, 0, 'C', true);
        $pdf->Cell(16, 0, $bladeDamage->getSize().'cm', 1, 0, 'C', true);
        $pdf->Cell(86, 0, $this->dr->getlocalizedDesciption($bladeDamage->getDamage()->getId(), $this->locale), 1, 0, 'L', true);
        $pdf->setBackgroundHexColor($bladeDamage->getDamageCategory()->getColour());
        $pdf->Cell(0, 0, $bladeDamage->getDamageCategory()->getCategory(), 1, 1, 'C', true);
        $pdf->setWhiteBackground();
    }
}
