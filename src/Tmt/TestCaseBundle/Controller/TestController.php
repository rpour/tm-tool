<?php

namespace Tmt\TestCaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/project/{projectId}/test/{testcaseId}")
 */
class TestController extends Controller {

    /**
     * @Route("/", name="tmt_test_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($projectId, $testcaseId) {
        $testService = $this->get('tmt.test');
        return array(
            'tests' => $testService->getAll()
        );
    }

    /**
     * @Route("/run", name="tmt_test_run")
     * @Method("GET")
     * @Template()
     */
    public function runAction($projectId, $testcaseId) {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_USER'))
            throw new AccessDeniedException();

        $testcaseService = $this->get('tmt.testcase');

        return array(
            'testcase' => $testcaseService->get($testcaseId)
        );
    }

    /**
     * @Route("/create", name="tmt_test_create")
     * @Method("POST")
     * @Template("TmtTestCaseBundle:Test:run.html.twig")
     */
    public function createAction($projectId, $testcaseId) {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_USER'))
            throw new AccessDeniedException();

        $testService = $this->get('tmt.test');

        $result = $testService->create();

        if (count($result['errors']) === 0) {
            return $this->redirect($this->generateUrl(
                'tmt_testcase_index', array('projectId' => $projectId)));
        }

        return array(
            'errors' => $result['errors'],
            'testcase' => $result['testcase']
        );
    }
}