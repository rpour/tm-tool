<?php

namespace Tmt\TestCaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
        return array();
    }

    /**
     * @Route("/run", name="tmt_test_run")
     * @Method("GET")
     * @Template()
     */
    public function runAction($projectId, $testcaseId) {
        $testcaseService = $this->get('tmt.testcase');

        return array(
            'testcase' => $testcaseService->get($testcaseId)
        );
    }

    /**
     * @Route("/create", name="tmt_test_create")
     * @Method("POST")
     */
    public function createAction($projectId, $testcaseId) {
        $testcaseService = $this->get('tmt.test');

        return array(
            'testcase' => $testcaseService->get($testcaseId)
        );
    }
}