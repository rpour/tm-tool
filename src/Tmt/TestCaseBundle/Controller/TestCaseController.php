<?php

namespace Tmt\TestCaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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

        return array(
            'testcases' => $testcaseService->getAll()
        );
    }

    /**
     * @Route("/new", name="tmt_testcase_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($projectId) {
        return array();
    }

    /**
     * @Route("/create", name="tmt_testcase_create")
     * @Method("POST")
     * @Template("TmtTestCaseBundle:TestCase:new.html.twig")
     */
    public function createAction($projectId) {
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
     * @Route("/remove/{testcaseId}", name="tmt_testcase_delete")
     * @Method("POST")
     */
    public function removeAction($projectId, $testcaseId) {
        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->remove($testcaseService->get($testcaseId));

        return $this->redirect($this->generateUrl(
            'tmt_testcase_index', array('projectId' => $projectId)));
    }
}
