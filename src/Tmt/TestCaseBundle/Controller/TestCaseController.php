<?php

namespace Tmt\TestCaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/testcase")
 * @Template()
 */
class TestCaseController extends Controller
{
    /**
     * @Route("/", name="tmt_testcase_default_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
