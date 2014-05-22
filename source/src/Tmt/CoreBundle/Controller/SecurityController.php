<?php

namespace Tmt\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Tmt\CoreBundle\Component\PDF\PDFBuilder;

class SecurityController extends Controller
{
    /**
     *
     * @Route("/login/check", name="tmt_login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     *
     * @Route("/logout", name="tmt_logout")
     */
    public function logoutAction()
    {

    }

    /**
     *
     * @Route("/pdf")
     */
    public function pdfAction()
    {
        $darkBlue = '2980b9';
        $lightBlue = 'eaf5fb';

        $font  = $this->get('kernel')->getRootDir() . '/../web/bundles/tmtcore/css/fonts/icomoon.ttf';
        $pdf   = new PDFBuilder();

        // change to column
        $cell = $pdf->getCell();
        $cell
            ->setFontFile($font)
            ->setFontSize(14)
            ->setBorder(1)
            ->setWidth(20)
            ->setBackgroundColor($lightBlue)
            ->setBorderColor($darkBlue)
            ->setText('::icon::icon-user')
            ->draw()
            ->clear()
            ->ln();

        $rows = array();
        $rows[] =
        $table = $pdf->getTable();
        $table
            ->setFontFile($font)
            ->setFontSize(16)
            ->setBorderColor('dbe7ed')
            ->toggleBackground(array($lightBlue))
            ->setRowSize(20, 30, 100)
            ->setRowAlign('L', 'C', 'C')
            ->setRow('hallo', 'mallo', 'ballo')
            ->setRow('hallo2', '::icon::icon-check', 'ballo2')
            ->setRow('hallo', 'mallo', 'ballo')
            ->setBorder(1)
            ->draw();

        $pdf->raw->Ln(10);

        $cell = $pdf->getCell();
        $cell
            ->setFontSize(26)
            ->setText('Project')
            ->draw()
            ->clear()
            ->bold()
            ->setHeight(15)
            ->setFontSize(16)
            ->setText(date('d.m.Y'))
            ->setAlign('R')
            ->draw()
            ->ln()
            ->setFontSize(14)
            ->setText('test')
            ->setAlign('C')
            ->draw();

        $pdf->forceDownload();
    }
}
