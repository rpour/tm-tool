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
class TestCaseController extends Controller
{
    /**
     * @Route("/", name="tmt_testcase_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($projectId)
    {
        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);
        $testService     = $this->get('tmt.test');
        $testcases       = $testcaseService->getAll();

        $count = array();
        foreach ($testcases as $testcase) {
            $result = $testService->getPassedCount($testcase->getId());

            $count[$testcase->getId()] = array(
                'passed' => $result['passed'],
                'notpassed' => $result['notpassed']
            );
        }

        return array(
            'projectId'  => $projectId,
            'testcases' => $testcases,
            'count' => $count
        );
    }

    /**
     * @Route("/new", name="tmt_testcase_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($projectId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN')) {
            throw new AccessDeniedException();
        }

        return array('projectId' => $projectId);
    }

    /**
     * @Route("/create", name="tmt_testcase_create")
     * @Method("POST")
     * @Template("TmtTestCaseBundle:TestCase:new.html.twig")
     */
    public function createAction($projectId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);
        $errors          = $testcaseService->create();

        if (count($errors) === 0) {
            return $this->redirect(
                $this->generateUrl(
                    'tmt_testcase_index',
                    array('projectId' => $projectId)
                )
            );
        }

        return array(
            'projectId'  => $projectId,
            'errors' => (string)$errors
        );
    }

    /**
     * @Route("/edit/{testcaseId}", name="tmt_testcase_edit")
     * @Method("GET")
     * @Template("TmtTestCaseBundle:TestCase:new.html.twig")
     */
    public function editAction($projectId, $testcaseId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);

        return array(
            'projectId' => $projectId,
            'update'    => true,
            'testcase'  => $testcaseService->get($testcaseId)
        );
    }

    /**
     * @Route("/update/{testcaseId}", name="tmt_testcase_update")
     * @Method("POST")
     * @Template("TmtTestCaseBundle:TestCase:new.html.twig")
     */
    public function updateAction($projectId, $testcaseId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);

        $errors = $testcaseService->update($testcaseId);

        if (count($errors) === 0) {
            return $this->redirect(
                $this->generateUrl(
                    'tmt_testcase_index',
                    array('projectId' => $projectId)
                )
            );
        }

        return array(
            'projectId' => $projectId,
            'update'    => true,
            'error'     => $errors,
            'testcase'  => $testcaseService->get($testcaseId)
        );
    }


    /**
     * @Route("/remove/{testcaseId}", name="tmt_testcase_confirm")
     * @Method("GET")
     * @Template()
     */
    public function confirmAction($projectId, $testcaseId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_PROJECT_ADMIN')) {
            throw new AccessDeniedException();
        }

        return array(
            'projectId'  => $projectId,
            'testcaseId' => $testcaseId,
        );
    }

    /**
     * @Route("/remove/{testcaseId}", name="tmt_testcase_remove")
     * @Method("POST")
     */
    public function removeAction($projectId, $testcaseId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);
        $testcaseService->remove($testcaseService->get($testcaseId));

        return $this->redirect(
            $this->generateUrl(
                'tmt_testcase_index',
                array('projectId' => $projectId)
            )
        );
    }

    /**
     * @Route("/pdf", name="tmt_testcase_pdf")
     * @Method("GET")
     */
    public function pdfAction($projectId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_PROJECT_ADMIN')) {
            throw new AccessDeniedException();
        }

        $projectService  = $this->get('tmt.project');
        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);
        $testService     = $this->get('tmt.test');
        $project         = $projectService->get($projectId);

        $testcasesArray = array();
        $testcases = $testcaseService->getAll();

        $pdf = new TestCasePdf(
            $project->getName(),
            $this->get('kernel')->getRootDir() . '/../web/bundles/tmtcore/css/fonts/icomoon.ttf'
        );

        $pdf->h1($project->getName());

        // Testcases
        foreach ($testcases as $testcase) {
            $testService->setTestCaseId($testcase->getId());
            $tests = $testService->getAll();

            $pdf->drawTestcase(
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


        // Test
        foreach ($testcasesArray as $testcase) {
            $pdf->h2($testcase['case']->getTitle());

            foreach ($testcase['tests'] as $test) {
                $data = json_decode($test->getData());
                $date = $test->getDate();

                $pdf->drawTest(
                    $test->getPassed(),
                    $date->format('d.m.Y'),
                    $test->getUsername(),
                    $data->version
                );
            }

        }

        $pdf->download();
    }
}
