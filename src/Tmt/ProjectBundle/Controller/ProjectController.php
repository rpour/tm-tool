<?php

namespace Tmt\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/project")
 * @Template()
 */
class ProjectController extends Controller
{
    /**
     * @Route("/", name="tmt_project_default_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
