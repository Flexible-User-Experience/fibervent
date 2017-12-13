<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Windfarm;
use AppBundle\Enum\WindfarmLanguageEnum;
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
    /**
     * @param Windfarm $windfarm
     * @param array $damageCategories
     * @param array $audits
     *
     * @return \TCPDF
     */
    public function build(Windfarm $windfarm, $damageCategories, $audits)
    {
        $this->locale = WindfarmLanguageEnum::getEnumArray()[$windfarm->getLanguage()];
        $this->ts->setLocale($this->locale);

        /** @var CustomTcpdf $pdf */
        $pdf = $this->doInitialConfig($windfarm, $audits);

        // Add a page
        $pdf->setPrintHeader(true);
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->setAvailablePageDimension();
        $pdf->setPrintFooter(true);

        // TODO Index section

        // Damage categories section
        if (!self::SHOW_V1_SECTIONS) {
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.damage_catalog.1_title'), '', false, 'L', true);
            $pdf->Ln(2);
            $pdf->setFontStyle(null, '', 9);
            $this->drawDamageCategoriesTable($pdf);
            $pdf->setBlueLine();
            $pdf->setWhiteBackground();
            $pdf->Ln(self::SECTION_SPACER_V_BIG);
        }

        // Windfarm inspection overview section
        if (!self::SHOW_V1_SECTIONS) {
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf_windfarm.inspection_overview.1_title'), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            $pdf->setFontStyle(null, '', 9);
            // resume damages table
            $this->drawWindfarmInspectionTableHeader($pdf, $damageCategories);
            /** @var Audit $audit */
            foreach ($audits as $audit) {
                $this->drawWindfarmInspectionTableBodyRow($pdf, $audit, $damageCategories);
            }
        }

        return $pdf;
    }

    /**
     * @param Windfarm $windfarm
     * @param array $audits
     *
     * @return \TCPDF
     */
    private function doInitialConfig(Windfarm $windfarm, $audits)
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
        if (self::SHOW_COVER_SECTION) {
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
//            $pdf->setBlueBackground();
//            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.5_turbine_model'), 'TB', 0, 'R', true);
//            $pdf->setFontStyle(null, '', 10);
//            $pdf->setWhiteBackground();
//            $pdf->Cell(0, 6, $windmill->getTurbine()->pdfToString(), 'TB', 1, 'L', true);
//            $pdf->setFontStyle(null, 'B', 10);
//            $pdf->setBlueBackground();
//            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.6_turbine_size'), 'TB', 0, 'R', true);
//            $pdf->setFontStyle(null, '', 10);
//            $pdf->setWhiteBackground();
//            $pdf->Cell(0, 6, $this->ts->trans('pdf.cover.6_turbine_size_value', ['%height%' => $windmill->getTurbine()->getTowerHeight(), '%diameter%' => $windmill->getTurbine()->getRotorDiameter(), '%length%' => $windmill->getBladeType()->getLength()]), 'TB', 1, 'L', true);
//            $pdf->setFontStyle(null, 'B', 10);
//            $pdf->setBlueBackground();
//            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.7_blade_type'), 'TB', 0, 'R', true);
//            $pdf->setFontStyle(null, '', 10);
//            $pdf->setWhiteBackground();
//            $pdf->Cell(0, 6, $windmill->getBladeType(), 'TB', 1, 'L', true);
//            $pdf->setFontStyle(null, 'B', 10);
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
            $pdf->Cell(0, 6, $windfarm->getMangerFullname(), 'TB', 1, 'L', true);
            // operators details
            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
            $pdf->setFontStyle(null, 'B', 10);
//            $pdf->setBlueBackground();
//            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.11_technicians'), 'TB', 0, 'R', true);
//            $pdf->setFontStyle(null, '', 10);
//            $pdf->setWhiteBackground();
//            $pdf->Cell(0, 6, implode(', ', $audit->getOperators()->getValues()), 'TB', 1, 'L', true);
            // final details
//            $pdf->SetXY(CustomTcpdf::PDF_MARGIN_LEFT, $pdf->GetY() + 10);
//            $pdf->setFontStyle(null, 'B', 10);
//            $pdf->setBlueBackground();
//            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.12_audit_type'), 'TB', 0, 'R', true);
//            $pdf->setFontStyle(null, '', 10);
//            $pdf->setWhiteBackground();
//            $pdf->Cell(0, 6, $this->ts->trans($audit->getTypeStringLocalized()), 'TB', 1, 'L', true);
//            $pdf->setFontStyle(null, 'B', 10);
//            $pdf->setBlueBackground();
//            $pdf->Cell(70, 6, $this->ts->trans('pdf_windfarm.cover.13_audit_date'), 'TB', 0, 'R', true);
//            $pdf->setFontStyle(null, '', 10);
//            $pdf->setWhiteBackground();
//            $pdf->Cell(0, 6, $audit->getPdfBeginDateString(), 'TB', 1, 'L', true);
//            $pdf->setFontStyle(null, 'B', 10);
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
}
