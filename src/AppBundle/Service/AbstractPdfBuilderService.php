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
     * @param DamageCategory $damageCategory
     * @param AuditWindmillBlade $auditWindmillBlade
     *
     * @return string
     */
    protected function markDamageCategory(DamageCategory $damageCategory, AuditWindmillBlade $auditWindmillBlade)
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