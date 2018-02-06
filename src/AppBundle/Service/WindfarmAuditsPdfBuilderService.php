<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\DamageCategory;
use AppBundle\Entity\Windfarm;
use AppBundle\Enum\WindfarmLanguageEnum;
use AppBundle\Factory\BladeDamageHelper;
use AppBundle\Factory\CategoryDamageHelper;
use AppBundle\Pdf\CustomTcpdf;

/**
 * Class Windfarm Audits Pdf Builder Service.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmAuditsPdfBuilderService extends AbstractPdfBuilderService
{
    const DAMAGE_HEADER_WIDTH_WINDFARM_INSPECTION = 80;
    const DAMAGE_HEADER_WIDTH_GENERAL_SUMMARY     = 60;
    const DAMAGE_HEADER_HEIGHT_GENERAL_SUMMARY    = 6;

    /**
     * @param Windfarm $windfarm
     * @param array    $damageCategories
     * @param array    $audits
     * @param integer  $year
     * @param array    $dateRanges
     *
     * @return \TCPDF
     */
    public function build(Windfarm $windfarm, $damageCategories, $audits, $year, $dateRanges)
    {
        $this->locale = WindfarmLanguageEnum::getEnumArray()[$windfarm->getLanguage()];
        $this->ts->setLocale($this->locale);

        /** @var CustomTcpdf $pdf */
        $pdf = $this->doInitialConfig($windfarm, $audits, $dateRanges);

        // Add a page
        $pdf->setPrintHeader(true);
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->setPrintFooter(true);
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, CustomTcpdf::PDF_MARGIN_TOP);

        // TODO Index section

        // Damage categories section
        if (self::WAC_SHOW_DAMAGE_CATEGORIES_SECTION) {
            $pdf->setBlackText();
            $pdf->setBlueLine();
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.damage_catalog.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            $pdf->setFontStyle(null, '', 9);
            // damages table
            $this->drawDamageCategoriesTable($pdf);
            $pdf->Ln(self::SECTION_SPACER_V_BIG);
        }

        // Windfarm inspection overview section
        if (self::WAC_SHOW_WINDFARM_INSPECTION_OVERVIEW_SECTION) {
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.inspection_overview.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            $pdf->setFontStyle(null, '', 9);
            // damages resume table
            $this->drawWindfarmInspectionTableHeader($pdf, $damageCategories);
            /** @var Audit $audit */
            foreach ($audits as $audit) {
                $this->drawWindfarmInspectionTableBodyRow($pdf, $audit, $damageCategories);
            }
            $pdf->Ln(self::SECTION_SPACER_V_BIG);
        }

        // Introduction section
        if (self::WAC_SHOW_INTRODUCTION_SECTION) {
            $pdf->setBlackText();
            $pdf->setBlueLine();
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.intro.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.intro.2_description', ['%windfarm%' => $windfarm->getName(), '%year%' => $year]), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            // introduction table
            $this->drawIntroductionTable($pdf);
            $pdf->Ln(self::SECTION_SPACER_V_BIG);
        }

        // Inspection description section
        if (self::WAC_SHOW_INSPECTION_DESCRIPTION_SECTION) {
            $pdf->setBlackText();
            $pdf->setBlueLine();
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.inspection_description.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            $this->drawInspectionDescriptionSection($pdf, 1); // TODO find right diagram type from audits collection
            $pdf->AddPage();
        }

        // General summary of damages section
        if (self::WAC_SHOW_GENERAL_SUMMARY_SECTION) {
            $pdf->setBlackText();
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.general_summary.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.general_summary.2_description'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
            // damages resume table
            $this->drawGeneralSummaryOfDamageTableHeader($pdf, $damageCategories);
            /** @var Audit $audit */
            foreach ($audits as $audit) {
                $this->drawGeneralSummaryOfDamageTableBodyRow($pdf, $audit, $damageCategories);
            }
            $pdf->AddPage();
        }

        // Individual summary of damages section
        if (self::WAC_SHOW_INDIVIDUAL_SUMMARY_SECTION) {
            $pdf->setBlackText();
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.individual_summary.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Ln(self::SECTION_SPACER_V_BIG);
            /** @var Audit $audit */
            foreach ($audits as $audit) {
                // Damages section
                $this->drawAuditDamage($pdf, $audit, true);
                $pdf->AddPage();
            }
        }

        // Contact section
        if (self::WAC_SHOW_CONTACT_SECTION) {
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.contact_section.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V_BIG / 2);
            $this->drawContactSection($pdf);
        }

        return $pdf;
    }

    /**
     * @param Windfarm $windfarm
     * @param array    $audits
     * @param array    $dateRanges
     *
     * @return \TCPDF
     */
    private function doInitialConfig(Windfarm $windfarm, $audits, $dateRanges)
    {
        /** @var CustomTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->tha, $this->ts, $windfarm);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Fibervent');
        $pdf->SetTitle($this->ts->trans('pdf_windfarm.set_document_information.1_title').$windfarm->getId().' '.$windfarm->getName());
        $pdf->SetSubject($this->ts->trans('pdf_windfarm.set_document_information.2_subject').$windfarm->getName());
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

        // Cover section
        if (self::WAC_SHOW_COVER_SECTION) {
            // add start page
            $pdf->startPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT);
            // logo
            if ($pdf->getCustomer()->isShowLogoInPdfs() && $pdf->getCustomer()->getImageName()) {
                // customer has logo
                $pdf->Image($this->uh->asset($pdf->getCustomer(), 'imageFile'), CustomTcpdf::PDF_MARGIN_LEFT + 12, 45, 55, 0, '', '', 'T', 2, 300, '', false, false, 0, false, false, false);
                $pdf->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape_hires.jpg'), 100, 45, 78, 0, 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                // customer hasn't logo
                $pdf->Image($this->tha->getUrl('/bundles/app/images/fibervent_logo_white_landscape_hires.jpg'), '', 45, 130, '', 'JPEG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
            }
            // main detail section
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, 100);
            $pdf->setFontStyle(null, 'B', 14);
            $pdf->setBlackText();
            $pdf->setBlueLine();
            $pdf->setBlueBackground();
            $pdf->Cell(0, 8, $this->ts->trans('pdf_windfarm.cover.1_inspection', array('%windfarm%' => $windfarm->getName())), 'TB', 1, 'C', true);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 8, $this->ts->trans('pdf_windfarm.cover.2_resume'), 'TB', 1, 'C', true);
            $pdf->Cell(0, 8, $windfarm->getPdfLocationString(), 'TB', 1, 'C', true);
            $pdf->setFontStyle();
            // table detail section
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.3_country'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windfarm->getState()->getCountryName().' ('.$windfarm->getState()->getName().')', 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.4_windfarm'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windfarm->getName(), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.5_turbine_model'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, implode(', ', $this->wbbs->getInvolvedTurbinesInAuditsList($audits)), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.6_turbine_size'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, implode(', ', $this->wbbs->getInvolvedTurbineModelsInAuditsList($audits)), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.7_blade_type'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, implode(', ', $this->wbbs->getInvolvedBladesInAuditsList($audits)), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.8_total_turbines'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans('pdf_windfarm.cover.8_total_turbines_value', ['%windmills%' => $windfarm->getWindmillAmount(), '%power%' => $windfarm->getPower()]), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.9_startup_year'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans('pdf_windfarm.cover.9_startup_year_value', ['%year%' => $windfarm->getYear(), '%diff%' => $windfarm->getYearDiff()]), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.10_om_regional_manager'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $windfarm->getManagerFullname(), 'TB', 1, 'L', true);
            // operators details
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.11_technicians'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, implode(', ', $this->wbbs->getInvolvedTechniciansInAuditsList($audits)), 'TB', 1, 'L', true);
            // final details
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.12_audit_type'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, implode(', ', $this->wbbs->getInvolvedAuditTypesInAuditsList($audits)), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf.cover.13_audit_date'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, implode(' · ', $this->wbbs->getInvolvedAuditDatesInAuditsList($dateRanges)), 'TB', 1, 'L', true);
            $pdf->setFontStyle(null, 'B', 10);
            $pdf->setBlueBackground();
            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.14_blades_amout'), 'TB', 0, 'R', true);
            $pdf->setFontStyle(null, '', 10);
            $pdf->setWhiteBackground();
            $pdf->Cell(0, 6, $this->ts->trans('pdf_windfarm.cover.14_blades_amout_value', array('%audits_amount%' => count($audits))), 'TB', 1, 'L', true);
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
     * @param array $damageCategories
     */
    private function drawWindfarmInspectionTableHeader(CustomTcpdf $pdf, $damageCategories)
    {
        $pdf->setBlueLine();
        $pdf->setBlueBackground();
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(50, 12, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.1_number'), 1, 0, 'C', true);
        $pdf->Cell(35, 12, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.2_blade'), 1, 0, 'C', true);
        $pdf->Cell(self::DAMAGE_HEADER_WIDTH_WINDFARM_INSPECTION, 6, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.3_damage_class'), 1, 1, 'C', true);
        $pdf->SetX(CustomTcpdf::PDF_MARGIN_LEFT + 85);
        $pdf->setFontStyle(null, '', 9);
        /** @var DamageCategory $dc */
        foreach ($damageCategories as $key => $dc) {
            $pdf->setBackgroundHexColor($dc->getColour());
            $pdf->Cell(self::DAMAGE_HEADER_WIDTH_WINDFARM_INSPECTION / count($damageCategories), 6, $dc->getCategory(), 1, ($key + 1 == count($damageCategories)), 'C', true);
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
    private function drawWindfarmInspectionTableBodyRow(CustomTcpdf $pdf, Audit $audit, $damageCategories)
    {
        $windmillBladesDamagesHelper = $this->wbdhf->buildWindmillBladesDamagesHelper($audit);
        $pdf->setWhiteBackground();
        $pdf->setFontStyle(null, '', 9);
        $pdf->Cell(50, self::DAMAGE_HEADER_HEIGHT_GENERAL_SUMMARY * count($windmillBladesDamagesHelper->getBladeDamages()), $windmillBladesDamagesHelper->getWindmillShortCode(), 1, 0, 'C', 1, '', 0);
        /** @var BladeDamageHelper $bladeDamageHelper */
        foreach ($windmillBladesDamagesHelper->getBladeDamages() as $bladeDamageHelper) {
            $pdf->SetX(CustomTcpdf::PDF_MARGIN_LEFT + 50);
            $pdf->Cell(35, 6, $bladeDamageHelper->getBlade(), 1, 0, 'C', true);
            /** @var CategoryDamageHelper $damageHelper */
            foreach ($bladeDamageHelper->getCategories() as $key => $damageHelper) {
                $pdf->Cell(self::DAMAGE_HEADER_WIDTH_WINDFARM_INSPECTION / count($damageCategories), 6, $damageHelper->getMark(), 1, ($key + 1 == count($damageCategories)), 'C', true);
            }
        }
    }

    /**
     * @param CustomTcpdf $pdf
     * @param array       $damageCategories
     */
    private function drawGeneralSummaryOfDamageTableHeader(CustomTcpdf $pdf, $damageCategories)
    {
        $pdf->setBlueBackground();
        $pdf->setBlueLine();
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(20, 12, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.1_number'), 1, 0, 'C', true);
        $pdf->Cell(10, 12, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.2_blade'), 1, 0, 'C', true);
        $pdf->Cell(self::DAMAGE_HEADER_WIDTH_GENERAL_SUMMARY, 6, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.3_damage_class'), 1, 0, 'C', true);
        $pdf->Cell($pdf->availablePageWithDimension - self::DAMAGE_HEADER_WIDTH_GENERAL_SUMMARY - 30, 12, $this->ts->trans('pdf_windfarm.inspection_overview.2_table_header.4_damages'), 1, 1, 'C', true);
        $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT + 30, $pdf->getY() - 6);
        $pdf->setFontStyle(null, '', 9);
        /** @var DamageCategory $dc */
        foreach ($damageCategories as $key => $dc) {
            $pdf->setBackgroundHexColor($dc->getColour());
            $pdf->Cell(self::DAMAGE_HEADER_WIDTH_GENERAL_SUMMARY / count($damageCategories), 6, $dc->getCategory(), 1, ($key + 1 == count($damageCategories)), 'C', true);
        }
        $pdf->setWhiteBackground();
    }

    /**
     * @param CustomTcpdf $pdf
     * @param Audit       $audit
     * @param array       $damageCategories
     */
    private function drawGeneralSummaryOfDamageTableBodyRow(CustomTcpdf $pdf, Audit $audit, $damageCategories)
    {
        $windmillBladesDamagesHelper = $this->wbdhf->buildWindmillBladesDamagesHelper($audit);
        $pdf->setWhiteBackground();
        $pdf->setFontStyle(null, '', 9);
        $currentY = $pdf->GetY();
        /** @var BladeDamageHelper $bladeDamageHelper */
        foreach ($windmillBladesDamagesHelper->getBladeDamages() as $bladeDamageHelper) {
            $pdf->SetX(CustomTcpdf::PDF_MARGIN_LEFT + 20);
            $pdf->Cell(10, $bladeDamageHelper->getRowPdfHeight(), $bladeDamageHelper->getBlade(), 1, 0, 'C', true);
            /** @var CategoryDamageHelper $damageHelper */
            foreach ($bladeDamageHelper->getCategories() as $key => $damageHelper) {
                if ($damageHelper->getMark() == CategoryDamageHelper::MARK) {
                    $pdf->setBackgroundHexColor($damageHelper->getColor());
                }
                $pdf->MultiCell(self::DAMAGE_HEADER_WIDTH_GENERAL_SUMMARY / count($damageCategories), $bladeDamageHelper->getRowPdfHeight(), $damageHelper->getLetterMarksToString(), 1, 'C', 1, 0, '', '', true, 0, false, true, $bladeDamageHelper->getRowPdfHeight(), 'M', false);
                $pdf->setWhiteBackground();
            }
            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
            $pdf->MultiCell($pdf->availablePageWithDimension - self::DAMAGE_HEADER_WIDTH_GENERAL_SUMMARY - 30, $bladeDamageHelper->getRowPdfHeight(), $bladeDamageHelper->getDamagesToString(), 1, 'L', 1, 1, '', '', true, 0, false);
        }
        $pdf->SetY($currentY);
        // Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
        $pdf->Cell(20, $windmillBladesDamagesHelper->getTotalPdfHeight(), $windmillBladesDamagesHelper->getWindmillShortCode(), 1, 1, 'C', 1, '', 0);
    }
}
