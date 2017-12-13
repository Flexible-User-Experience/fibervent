<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use AppBundle\Enum\WindfarmLanguageEnum;
use AppBundle\Pdf\CustomTcpdf;

/**
 * Class Audit Pdf Builder Service.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class AuditPdfBuilderService extends AbstractPdfBuilderService
{
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
            $pdf->Ln(self::SECTION_SPACER_V);
            $pdf->setFontStyle(null, '', 9);
            $pdf->Write(0, $this->ts->trans('pdf.intro.2_description', ['%windfarm%' => $windfarm->getName(), '%begin%' => $audit->getPdfBeginDateString(), '%end%' => $audit->getPdfEndDateString()]), '', false, 'L', true);
            $pdf->Ln(self::SECTION_SPACER_V);
            // Introduction table
            $this->drawIntroductionTable($pdf);
            $pdf->Ln(self::SECTION_SPACER_V_BIG);

            // Damage categories section
            if (self::SHOW_DAMAGE_CATEGORIES_SECTION) {
                // Damages categorization
                $pdf->setFontStyle(null, 'B', 11);
                $pdf->Write(0, $this->ts->trans('pdf.damage_catalog.1_title'), '', false, 'L', true);
                $pdf->Ln(self::SECTION_SPACER_V);
                $pdf->setFontStyle(null, '', 9);
                $pdf->Write(0, $this->ts->trans('pdf.damage_catalog.2_subtitle'), '', false, 'L', true);
                $pdf->Ln(self::SECTION_SPACER_V);
                // Damages table
                $this->drawDamageCategoriesTable($pdf);
                $pdf->setBlueLine();
                $pdf->setWhiteBackground();
                $pdf->Ln(self::SECTION_SPACER_V_BIG);
            }

            // Inspection description
            if (self::SHOW_INSPECTION_DESCRIPTION_SECTION) {
                $pdf->setFontStyle(null, 'B', 11);
                $pdf->Write(0, $this->ts->trans('pdf.audit_description.1_title'), '', false, 'L', true);
                $pdf->Ln(self::SECTION_SPACER_V);
                $this->drawInspectionDescriptionSection($pdf, $audit->getDiagramType());
                $pdf->AddPage();
            }
        }

        // Damages section
        $this->drawAuditDamager($pdf, $audit);

        // Contact section
        if (self::SHOW_CONTACT_SECTION) {
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $this->ts->trans('pdf.inspection_description.1_contact'), '', false, 'L', true);
            $pdf->Ln(5);
            $this->drawContactSection($pdf);
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
        $pdf = $this->tcpdf->create($this->tha, $this->ts, $audit->getWindfarm());
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
        if (self::SHOW_COVER_SECTION) {
            // add start page
            $pdf->startPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT);
            // logo
            if ($audit->getCustomer()->isShowLogoInPdfs() && $audit->getCustomer()->getImageName()) {
                // customer has logo
                // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
                $pdf->Image($this->uh->asset($audit->getCustomer(), 'imageFile'), CustomTcpdf::PDF_MARGIN_LEFT + 12, 45, 55, 0, '', '', 'T', 2, 300, '', false, false, 0, false, false, false);
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
}
