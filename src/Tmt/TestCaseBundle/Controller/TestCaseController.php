<?php

namespace Tmt\TestCaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Tmt\TestCaseBundle\Component\TestCasePdf;

/**
 * @Route("/project/{projectId}/testcase")
 */
class TestCaseController extends Controller {

    /**
     * @Route("/", name="tmt_testcase_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($projectId) {
        $testcaseService = $this->get('tmt.testcase');
        $testService = $this->get('tmt.test');
        $testcases = $testcaseService->getAll();

        $count = array();
        foreach ($testcases as $testcase) {
            $result = $testService->getPassedCount($testcase->getId());

            $count[$testcase->getId()] = array(
                'passed' => $result['passed'],
                'notpassed' => $result['notpassed']
            );
        }

        return array(
            'testcases' => $testcases,
            'count' => $count
        );
    }

    /**
     * @Route("/new", name="tmt_testcase_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($projectId) {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN'))
            throw new AccessDeniedException();

        return array();
    }

    /**
     * @Route("/create", name="tmt_testcase_create")
     * @Method("POST")
     * @Template("TmtTestCaseBundle:TestCase:new.html.twig")
     */
    public function createAction($projectId) {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN'))
            throw new AccessDeniedException();

        $testcaseService = $this->get('tmt.testcase');
        $errors = $testcaseService->create();

        if (count($errors) === 0)
            return $this->redirect($this->generateUrl(
                'tmt_testcase_index', array('projectId' => $projectId)));

        return array('errors' => (string)$errors);
    }

    /**
     * @Route("/edit/{testcaseId}", name="tmt_testcase_edit")
     * @Method("GET")
     * @Template("TmtTestCaseBundle:TestCase:new.html.twig")
     */
    public function editAction($projectId, $testcaseId) {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN'))
            throw new AccessDeniedException();

        $testcaseService = $this->get('tmt.testcase');

        return array(
            'update'   => true,
            'testcase' => $testcaseService->get($testcaseId)
        );
    }

    /**
     * @Route("/update/{testcaseId}", name="tmt_testcase_update")
     * @Method("POST")
     * @Template("TmtTestCaseBundle:TestCase:new.html.twig")
     */
    public function updateAction($projectId, $testcaseId) {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN'))
            throw new AccessDeniedException();

        $testcaseService = $this->get('tmt.testcase');
        $errors = $testcaseService->update($testcaseId);

        if (count($errors) === 0)
            return $this->redirect($this->generateUrl(
                'tmt_testcase_index', array('projectId' => $projectId)));

        return array(
            'update'   => true,
            'error'    => $errors,
            'testcase' => $testcaseService->get($testcaseId)
        );
    }


    /**
     * @Route("/remove/{testcaseId}", name="tmt_testcase_confirm")
     * @Method("GET")
     * @Template()
     */
    public function confirmAction($projectId, $projectId) {
        if (false === $this->get('security.context')->isGranted('ROLE_PROJECT_ADMIN'))
            throw new AccessDeniedException();

        return array();
    }

    /**
     * @Route("/remove/{testcaseId}", name="tmt_testcase_remove")
     * @Method("POST")
     */
    public function removeAction($projectId, $testcaseId) {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN'))
            throw new AccessDeniedException();

        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->remove($testcaseService->get($testcaseId));

        return $this->redirect($this->generateUrl(
            'tmt_testcase_index', array('projectId' => $projectId)));
    }

    /**
     * @Route("/pdf", name="tmt_testcase_pdf")
     * @Method("GET")
     */
    public function pdfAction($projectId) {
        if (false === $this->get('security.context')->isGranted('ROLE_PROJECT_ADMIN'))
            throw new AccessDeniedException();

        $projectService = $this->get('tmt.project');
        $testcaseService = $this->get('tmt.testcase');
        $testService = $this->get('tmt.test');
        $project = $projectService->get($projectId);

        $testcasesArray = array();
        $testcases = $testcaseService->getAll();

        // $html = $this->render('TmtProjectBundle:Project:pdf.html.twig', array(
        //     "project" => $project,
        //     "testcases" => $testcasesArray
        // ));

        $filename = preg_replace('/\W/', '', strtolower($project->getName()))
            . date('_Y-m-d')
            . '.pdf';

        $pdf = new TestCasePdf();
        $pdf->SetTitle($project->getName());
        $pdf->setIconFontFile($this->get('kernel')->getRootDir() . '/../web/bundles/tmtcore/css/fonts/icomoon.ttf');

        $pdf->SetFontSize(25);
        $pdf->Cell(0, 15, $project->getName(), 0, 0, 'L');
        $pdf->SetFontSize(14);
        $pdf->Cell(0, 15, date('d.m.Y'), 0, 1, 'R');

        $pdf->_setFontSize(14);

        foreach ($testcases as $testcase) {
            $testService->setTestCaseId($testcase->getId());
            $tests = $testService->getAll();

            $pdf->drawTestCase(
                $testcase->getLastState(),
                $testcase->getTitle(),
                count($tests)
            );

            $testcasesArray[] = array(
                'case' => $testcase,
                'tests' => $tests
            );
        }

        unset($testcase, $test);

        $pdf->Ln(8);
        $pdf->SetFontSize(10);

        foreach ($testcasesArray as $testcase) {
            $pdf->_write($testcase['case']->getTitle(), 0);
            $pdf->Ln(8);

            foreach ($testcase['tests'] as $test) {
                $data = json_decode($test->getData());

                if ($test->getPassed())
                    $pdf->_icon('icon-check', '4AB471');
                else
                    $pdf->_icon('icon-warning', 'CF5C60');

                $date = $test->getDate();
                $pdf->_write($date->format('d.m.Y'), 40);

                $pdf->_icon('icon-user');
                $pdf->_write($test->getUsername(), 40);

                $pdf->_write($data->version, 30, null, 'R');

                $pdf->Ln(8);
            }
             $pdf->Ln(8);
        }

        $pdf->Output($filename, 'D');
    }
}
