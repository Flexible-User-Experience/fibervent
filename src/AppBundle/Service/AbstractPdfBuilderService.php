<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\BladePhoto;
use AppBundle\Entity\DamageCategory;
use AppBundle\Entity\Observation;
use AppBundle\Entity\Photo;
use AppBundle\Factory\WindmillBladesDamagesHelperFactory;
use AppBundle\Manager\ObservationManager;
use AppBundle\Pdf\CustomTcpdf;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\DamageRepository;
use AppBundle\Repository\BladeDamageRepository;
use AppBundle\Repository\DamageCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class AbstractPdfBuilderService.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class AbstractPdfBuilderService
{
    // Vertical spacers
    const SECTION_SPACER_V = 2;
    const SECTION_SPACER_V_BIG = 10;

    // Blade Damage Summary
    const BDS_SHOW_DAMAGES_TABLE = true;
    const BDS_SHOW_DIAGRAM = true;
    const BDS_SHOW_DIAGRAM_DEBUG_GRID = false;
    const BDS_SHOW_OBSERVATIONS_TABLE = true;
    const BDS_SHOW_WINDMILL_IMAGES = true;
    const BDS_SHOW_BLADE_IMAGES = true;

    // Single Audit
    const SA_SHOW_COVER_SECTION = true;
    const SA_SHOW_INTRODUCTION_SECTION = true;
    const SA_SHOW_DAMAGE_CATEGORIES_SECTION = true;
    const SA_SHOW_INSPECTION_DESCRIPTION_SECTION = true;
    const SA_SHOW_INDIVIDUAL_DAMAGES_SUMMARY_SECTION = true;
    const SA_SHOW_CONTACT_SECTION = true;

    // Windfarm Audits Collection
    const WAC_SHOW_COVER_SECTION = true;
    const WAC_SHOW_DAMAGE_CATEGORIES_SECTION = true;
    const WAC_SHOW_WINDFARM_INSPECTION_OVERVIEW_SECTION = true;
    const WAC_SHOW_INTRODUCTION_SECTION = true;
    const WAC_SHOW_INSPECTION_DESCRIPTION_SECTION = true;
    const WAC_SHOW_GENERAL_SUMMARY_SECTION = true;
    const WAC_SHOW_INDIVIDUAL_SUMMARY_SECTION = true;
    const WAC_SHOW_CONTACT_SECTION = true;

    /**
     * @var TCPDFController
     */
    protected $tcpdf;

    /**
     * @var CacheManager
     */
    protected $cm;

    /**
     * @var UploaderHelper
     */
    protected $uh;

    /**
     * @var SmartAssetsHelperService
     */
    protected $sahs;

    /**
     * @var Translator
     */
    protected $ts;

    /**
     * @var DamageRepository
     */
    protected $dr;

    /**
     * @var DamageCategoryRepository
     */
    protected $dcr;

    /**
     * @var BladeDamageRepository
     */
    protected $bdr;

    /**
     * @var CustomerRepository
     */
    protected $cr;

    /**
     * @var AuditModelDiagramBridgeService
     */
    protected $amdb;

    /**
     * @var WindfarmBuilderBridgeService
     */
    protected $wbbs;

    /**
     * @var WindmillBladesDamagesHelperFactory
     */
    protected $wbdhf;

    /**
     * @var ObservationManager
     */
    protected $om;

    /**
     * @var string
     */
    protected $locale;

    /**
     * Methods.
     */

    /**
     * AuditPdfBuilderService constructor.
     *
     * @param TCPDFController                    $tcpdf
     * @param CacheManager                       $cm
     * @param UploaderHelper                     $uh
     * @param SmartAssetsHelperService           $sahs
     * @param Translator                         $ts
     * @param DamageRepository                   $dr
     * @param DamageCategoryRepository           $dcr
     * @param BladeDamageRepository              $bdr
     * @param CustomerRepository                 $cr
     * @param AuditModelDiagramBridgeService     $amdb
     * @param WindfarmBuilderBridgeService       $wbbs
     * @param WindmillBladesDamagesHelperFactory $wbdhf
     * @param ObservationManager                 $om
     */
    public function __construct(TCPDFController $tcpdf, CacheManager $cm, UploaderHelper $uh, SmartAssetsHelperService $sahs, Translator $ts, DamageRepository $dr, DamageCategoryRepository $dcr, BladeDamageRepository $bdr, CustomerRepository $cr, AuditModelDiagramBridgeService $amdb, WindfarmBuilderBridgeService $wbbs, WindmillBladesDamagesHelperFactory $wbdhf, ObservationManager $om)
    {
        $this->tcpdf = $tcpdf;
        $this->cm = $cm;
        $this->uh = $uh;
        $this->sahs = $sahs;
        $this->ts = $ts;
        $this->dr = $dr;
        $this->dcr = $dcr;
        $this->bdr = $bdr;
        $this->cr = $cr;
        $this->amdb = $amdb;
        $this->wbbs = $wbbs;
        $this->wbdhf = $wbdhf;
        $this->om = $om;
    }

    /**
     * Draw introduction table.
     *
     * @param CustomTcpdf $pdf
     */
    protected function drawIntroductionTable(CustomTcpdf $pdf)
    {
        $pdf->setCellPaddings(20, 2, 20, 2);
        $pdf->setCellMargins(0, 0, 0, 0);
        $pdf->MultiCell(0, 0, $this->ts->trans('pdf.intro.3_list'), 1, 'L', false, 1, '', '', true, 0, true);
        $pdf->setCellPaddings(1, 1, 1, 1);
        $pdf->setCellMargins(0, 0, 0, 0);
    }

    /**
     * Draw damage categories full table.
     *
     * @param CustomTcpdf $pdf
     */
    protected function drawDamageCategoriesTable(CustomTcpdf $pdf)
    {
        $pdf->setBlackLine();
        $pdf->setBlueBackground();
        $pdf->setFontStyle(null, 'B', 9);
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
    }

    /**
     * Draw inspection description section.
     *
     * @param CustomTcpdf $pdf
     * @param int         $diagramType
     */
    protected function drawInspectionDescriptionSection(CustomTcpdf $pdf, $diagramType)
    {
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, $this->ts->trans('pdf.audit_description.2_description'), '', false, 'L', true);
        $pdf->Ln(self::SECTION_SPACER_V);
        // Audit description with windmill image schema
        $pdf->Image($this->sahs->getAbsoluteAssetFilePath('/bundles/app/images/tubrine_diagrams/'.$diagramType.'.jpg'), CustomTcpdf::PDF_MARGIN_LEFT + 50, $pdf->GetY(), null, 40);
    }

    /**
     * @param CustomTcpdf $pdf
     * @param Audit       $audit
     * @param bool        $showAuditMark
     */
    protected function drawAuditDamage(CustomTcpdf $pdf, Audit $audit, $showAuditMark = false)
    {
        $pdf->setCellPaddings(1, 1, 1, 1);
        $pdf->setCellMargins(0, 0, 0, 0);
        /** @var AuditWindmillBlade $auditWindmillBlade */
        foreach ($audit->getAuditWindmillBlades() as $key => $auditWindmillBlade) {
            $bladeDamages = $this->bdr->getItemsOfAuditWindmillBladeSortedByRadius($auditWindmillBlade);
            $pdf->setFontStyle(null, 'B', 11);
            $serialNumberSuffix = $auditWindmillBlade->getWindmillBlade()->getCode() ? ' - (S/N: '.($auditWindmillBlade->getWindmillBlade()->getCode()).')' : '';
            $pdf->Write(0, '3.'.($key + 1).' '.$this->ts->trans('pdf.audit_blade_damage.1_title').' '.($key + 1).$serialNumberSuffix.($showAuditMark ? ' ('.$audit->getWindmill()->getCode().')' : ''), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf.audit_blade_damage.2_description'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
            // damage table
            if (self::BDS_SHOW_DAMAGES_TABLE) {
                $pdf->setBlueLine();
                $this->drawDamageTableHeader($pdf);
                /** @var BladeDamage $bladeDamage */
                foreach ($bladeDamages as $sKey => $bladeDamage) {
                    $this->drawDamageTableBodyRow($pdf, $sKey, $bladeDamage);
                }
                $pdf->Ln(7);
            }

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

            if (self::BDS_SHOW_DIAGRAM) {
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

                if (self::BDS_SHOW_DIAGRAM_DEBUG_GRID) {
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

                $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 3);
                $pdf->StartTransform();
                $pdf->Rotate(90);
                // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
                $pdf->MultiCell(20, 0, $this->ts->trans('pdf.blade_damage_diagram.6_bs_l_short'), 0, 'C', 0, 0, '', '', true);
                $pdf->MultiCell(20, 0, $this->ts->trans('pdf.blade_damage_diagram.2_vs_s'), 0, 'C', 0, 0, '', '', true);
                $pdf->MultiCell(20, 0, $this->ts->trans('pdf.blade_damage_diagram.5_ba_l_short'), 0, 'C', 0, 0, '', '', true);
                $pdf->MultiCell(20, 0, $this->ts->trans('pdf.blade_damage_diagram.1_vp_s'), 0, 'C', 0, 0, '', '', true);
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
                    if (self::BDS_SHOW_DIAGRAM_DEBUG_GRID) {
                        $pdf->setRedLine();
                        $this->amdb->drawCenterPoint($pdf, $bladeDamage);
                        $pdf->setBlackLine();
                    }
                }

                $pdf->setWhiteBackground();
                $pdf->SetY($yBreakpoint + AuditModelDiagramBridgeService::DIAGRAM_HEIGHT + 10);
            }

            // Observations table
            if (count($auditWindmillBlade->getObservations()) > 0 && self::BDS_SHOW_OBSERVATIONS_TABLE) {
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
                    $pdf->MultiCell(16, $h, $this->om->getPdfBladeDamageNumber($observation), 1, 'C', 0, 1, CustomTcpdf::PDF_MARGIN_LEFT, '', true, 0, false, true, 0, 'M');
                }
                $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
            }

            // General blade damage images
            if (count($auditWindmillBlade->getBladePhotos()) > 0 && self::BDS_SHOW_WINDMILL_IMAGES) {
                $pdf->AddPage();
                $pdf->setFontStyle(null, 'B', 11);
                $pdf->Write(0, '3.'.($key + 1).'.'.$this->ts->trans('pdf.blade_damage_images.1_general_blade_views').' '.($key + 1).($showAuditMark ? ' ('.$audit->getWindmill()->getCode().')' : ''), '', false, 'L', true);
                $pdf->Ln(3);
                $pdf->setFontStyle(null, '', 9);
                $i = 0;
                /** @var BladePhoto $photo */
                foreach ($auditWindmillBlade->getBladePhotos() as $photo) {
                    if ($photo->getImageName()) {
                        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
                        $pdf->Image($this->cm->getBrowserPath($this->uh->asset($photo, 'imageFile'), '600x960'), CustomTcpdf::PDF_MARGIN_LEFT + (($i % 2) * 76) + 7, $pdf->GetY(), null, 115);
                        ++$i;
                        if (0 == $i % 2) {
                            $pdf->Ln(120);
                        }
                    }
                }
                $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
            }

            $pdf->AddPage();
            if (self::BDS_SHOW_BLADE_IMAGES) {
                // Damage images pages
                $pdf->setBlueLine();
                /** @var BladeDamage $bladeDamage */
                foreach ($bladeDamages as $sKey => $bladeDamage) {
                    $this->drawDamageTableHeader($pdf);
                    $this->drawDamageTableBodyRow($pdf, $sKey, $bladeDamage);
                    $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
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
    }

    /**
     * Draw contact final section.
     *
     * @param CustomTcpdf $pdf
     */
    protected function drawContactSection(CustomTcpdf $pdf)
    {
        $pdf->setFontStyle(null, '', 9);
        $pdf->Write(0, $this->ts->trans('pdf.inspection_description.2_description'), '', false, 'L', true);
        $pdf->Ln(self::SECTION_SPACER_V_BIG);
        $pdf->Cell(10, 0, '', 0, 0);
        $pdf->Cell(0, 0, $this->ts->trans('pdf.inspection_description.3_offices'), 0, 1, 'L', 0, '');
        $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
        $pdf->Cell(20, 0, '', 0, 0);
        $pdf->Cell(0, 0, 'Pol. Industrial Pla de Solans, Parcela 2', 0, 1, 'L', 0, '');
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
        $pdf->Ln(self::SECTION_SPACER_V_BIG);
        $pdf->Cell(10, 0, '', 0, 0);
        $pdf->Cell(0, 0, $this->ts->trans('pdf.inspection_description.4_phones_emails'), 0, 1, 'L', 0, '');
        $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
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
        $pdf->Cell(0, 0, 'David Margalef (+34 654 743 190)', 0, 1, 'L', 0, '');
        $pdf->setFontStyle(null, 'U', 9);
        $pdf->setBlueText();
        $pdf->Cell(20, 0, '', 0, 0);
        $pdf->Cell(0, 0, 'david.margalef@fibervent.com', 0, 1, 'L', 0, 'mailto:david.margalef@fibervent.com');
        $pdf->setFontStyle(null, '', 9);
        $pdf->setBlackText();
        $pdf->Ln(3);
        $pdf->Cell(20, 0, '', 0, 0);
        $pdf->Cell(0, 0, 'Joan Lluís Ballvé (+34 654 743 190)', 0, 1, 'L', 0, '');
        $pdf->setFontStyle(null, 'U', 9);
        $pdf->setBlueText();
        $pdf->Cell(20, 0, '', 0, 0);
        $pdf->Cell(0, 0, 'joanlluis.ballve@fibervent.com', 0, 1, 'L', 0, 'mailto:joanlluis.ballve@fibervent.com');
        $pdf->setFontStyle(null, '', 9);
        $pdf->setBlackText();
        $pdf->Ln(15);
        $pdf->Write(0, $this->ts->trans('pdf.inspection_description.5_gratitude'), '', false, 'L', true);
        $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
        $pdf->Write(0, 'FIBERVENT, S.L.', '', false, 'L', true);
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
