<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\DamageCategory;
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
 * Class AbstractPdfBuilderService
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class AbstractPdfBuilderService
{
    const SECTION_SPACER_V     = 2;
    const SECTION_SPACER_V_BIG = 10;
    const SHOW_COVER_SECTION   = true;
    const SHOW_V1_SECTIONS     = false;
    const SHOW_GRID_DEBUG      = true;
    const SHOW_ONLY_DIAGRAM    = false;
    const SHOW_DAMAGE_CATEGORIES_SECTION            = false;
    const SHOW_WINDFARM_INSPECTION_OVERVIEW_SECTION = false;
    const SHOW_INTRODUCTION_SECTION                 = false;
    const SHOW_INSPECTION_DESCRIPTION_SECTION       = false;
    const SHOW_INDIVIDUAL_SUMMARY_SECTION           = true;
    const SHOW_CONTACT_SECTION                      = false;

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
     * @var AssetsHelper
     */
    protected $tha;

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
     * @var string
     */
    protected $locale;

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
     * Draw introduction table
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
     * Draw damage categories full table
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
    }

    /**
     * Draw inspection description section
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
        $pdf->Image($this->tha->getUrl('/bundles/app/images/tubrine_diagrams/'.$diagramType.'.jpg'), CustomTcpdf::PDF_MARGIN_LEFT + 50, $pdf->GetY(), null, 40);
    }

    /**
     * Draw damage table header.
     *
     * @param CustomTcpdf $pdf
     * @param array $damageCategories
     */
    protected function drawWindfarmInspectionTableHeader(CustomTcpdf $pdf, $damageCategories)
    {
        $damageHeaderWidth = 80;
        $pdf->setBlueBackground();
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(50, 12, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.1_number'), 1, 0, 'C', true);
        $pdf->Cell(35, 12, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.2_blade'), 1, 0, 'C', true);
        $pdf->Cell($damageHeaderWidth, 6, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.3_damage_class'), 1, 1, 'C', true);
        $pdf->SetX(CustomTcpdf::PDF_MARGIN_LEFT + 85);
        $pdf->setFontStyle(null, '', 9);
        /** @var DamageCategory $dc */
        foreach ($damageCategories as $key => $dc) {
            $pdf->setBackgroundHexColor($dc->getColour());
            $pdf->Cell($damageHeaderWidth / count($damageCategories), 6, $dc->getCategory(), 1, ($key + 1 == count($damageCategories)), 'C', true);
        }
        $pdf->setWhiteBackground();
    }

    /**
     * Draw damage table header.
     *
     * @param CustomTcpdf $pdf
     * @param Audit $audit
     * @param array $damageCategories
     */
    protected function drawWindfarmInspectionTableBodyRow(CustomTcpdf $pdf, Audit $audit, $damageCategories)
    {
        $damageHeaderWidth = 80;
        $pdf->setWhiteBackground();
        $pdf->setFontStyle(null, '', 9);
        $pdf->Cell(50, 18, $audit->getWindmill()->getCode(), 1, 0, 'C', true);
        $i = 0;
        /** @var AuditWindmillBlade $auditWindmillBlade */
        foreach ($audit->getAuditWindmillBlades() as $auditWindmillBlade) {
            $i++;
            $pdf->SetX(CustomTcpdf::PDF_MARGIN_LEFT + 50);
            $pdf->Cell(35, 6, $i, 1, 0, 'C', true);
            /** @var DamageCategory $damageCategory */
            foreach ($damageCategories as $key => $damageCategory) {
                $pdf->Cell($damageHeaderWidth / count($damageCategories), 6, $this->markDamageCategory($damageCategory, $auditWindmillBlade), 1, ($key + 1 == count($damageCategories)), 'C', true);
            }
        }
    }

    /**
     * Draw contact final section
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
        $pdf->Ln(self::SECTION_SPACER_V_BIG);
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

    /**
     * @param DamageCategory $damageCategory
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return string
     */
    private function markDamageCategory(DamageCategory $damageCategory, AuditWindmillBlade $auditWindmillBlade)
    {
        $result = '';
        /** @var BladeDamage $bladeDamage */
        foreach ($auditWindmillBlade->getBladeDamages() as $bladeDamage) {
            if ($bladeDamage->getDamageCategory()->getId() == $damageCategory->getId()) {
                $result = 'X';

                break;
            }
        }

        return $result;
    }
}