<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Windfarm;
use AppBundle\Entity\Windmill;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

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
     * @var string $krd Kernel Root Dir
     */
    private $krd;

    /**
     * AuditPdfBuilderService constructor
     *
     * @param TCPDFController $tcpdf
     * @param CacheManager    $cm
     * @param UploaderHelper  $uh
     * @param string          $krd
     */
    public function __construct(TCPDFController $tcpdf, CacheManager $cm, UploaderHelper $uh, $krd)
    {
        $this->tcpdf = $tcpdf;
        $this->cm = $cm;
        $this->uh = $uh;
        $this->krd = $krd;
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
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);

        // Add start page
        $pdf->startPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT);

        // title
        $pdf->Write(0, 'FIBERVENT', '', 0, 'C', true, 0, false, false, 0);

        // main detail section
        $pdf->Write(0, '------', '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, 'INSPECCIÓN DE PALAS DEL PARQUE EÓLICO ' . $windfarm->getName(), '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, 'INFORME INDIVIDUAL AEROGENERADOR ' . $windmill->getCode(), '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, $windfarm->getPdfLocationString() . '', '', 0, 'C', true, 0, false, false, 0);

        // table detail section
        $pdf->Write(0, '------', '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, 'PAÍS/REGION: ' . $windfarm->getState()->getCountryName() . ' (' . $windfarm->getState()->getName() . ')' . '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'PARQUE EÓLICO: ' . $windfarm->getName(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'MODELO AEROGENERADOR: ' . $windmill->getPdfModelString(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'MODELO PALA: ' . $windmill->getBladeType()->getModel(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'TOTAL No. AG / Capacidad PE: ' . $windfarm->getPdfTotalPowerString(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Puesta en marcha (años del PE): ' . $windfarm->getPdfYearString(), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'O&M REGIONAL MANEGER: ' . $windfarm->getManager()->getFullname(), '', 0, 'L', true, 0, false, false, 0);

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

        return $pdf;
    }
}
