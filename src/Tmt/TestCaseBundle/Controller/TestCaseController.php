<?php

namespace Tmt\TestCaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
}
