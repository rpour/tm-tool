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
class TestController extends Controller
{

    /**
     * @Route("/", name="tmt_test_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($projectId, $testcaseId)
    {
        $testService = $this->get('tmt.test');
        $testService->setTestCaseId($testcaseId);
        $userAgentService = $this->get('tmt.useragent');

        $tests = $testService->getAll();
        for ($i=0; $i < count($tests); $i++) {
            $userAgent = $userAgentService->get(md5($tests[$i]->getUserAgent()));
            if (is_object($userAgent)) {
                $tests[$i]->setUserAgent($userAgent);
            }
        }

        return array(
            'projectId' => $projectId,
            'testcaseId' => $testcaseId,
            'tests'     => $tests
        );
    }

    /**
     * @Route("/run", name="tmt_test_run")
     * @Method("GET")
     * @Template()
     */
    public function runAction($projectId, $testcaseId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_USER')) {
            throw new AccessDeniedException();
        }

        $testcaseService = $this->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);

        $data = $testcaseService->get($testcaseId);
        $preData = null;
        $postData = null;

        if (!empty($data->getPreconditionId())) {
            $preData = $testcaseService->get($data->getPreconditionId());
        }

        if (!empty($data->getPostconditionId())) {
            $postData = $testcaseService->get($data->getPostconditionId());
        }

        return array(
            'projectId' => $projectId,
            'testcaseId' => $testcaseId,
            'testcase' => $testcaseService->get($testcaseId),
            'preTestcase' => $preData,
            'postTestcase' => $postData
        );
    }

    /**
     * @Route("/create", name="tmt_test_create")
     * @Method("POST")
     * @Template("TmtTestCaseBundle:Test:run.html.twig")
     */
    public function createAction($projectId, $testcaseId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_USER')) {
            throw new AccessDeniedException();
        }

        $testService = $this->get('tmt.test');
        $testService->setTestCaseId($testcaseId);

        $result = $testService->create($projectId);

        if (count($result['errors']) === 0) {
            return $this->redirect(
                $this->generateUrl(
                    'tmt_testcase_index',
                    array('projectId' => $projectId)
                )
            );
        }

        return array(
            'errors' => $result['errors'],
            'testcase' => $result['testcase']
        );
    }


    /**
     * @Route("/remove/{testId}", name="tmt_test_confirm")
     * @Method("GET")
     * @Template()
     */
    public function confirmAction($projectId, $testcaseId, $testId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_PROJECT_ADMIN')) {
            throw new AccessDeniedException();
        }

        return array(
            'projectId'  => $projectId,
            'testcaseId' => $testcaseId,
            'testId'     => $testId
        );
    }


    /**
     * @Route("/remove/{testId}", name="tmt_test_remove")
     * @Method("POST")
     */
    public function removeAction($projectId, $testcaseId, $testId)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TESTCASE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $testService = $this->get('tmt.test');
        $testService->remove($testService->get($testId));

        return $this->redirect(
            $this->generateUrl(
                'tmt_test_index',
                array(
                    'projectId' => $projectId,
                    'testcaseId' => $testcaseId
                )
            )
        );
    }
}
