<?php

namespace Tmt\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Tmt\ProjectBundle\Entity\Project;
use Tmt\ProjectBundle\Form\ProjectType;

/**
 * @Route("/")
 * @Template()
 */
class ProjectController extends Controller {
    /**
     * @Route("/", name="tmt_project_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $project = $this->get('tmt.project');

        return array(
            'projects' => $project->getAll()
        );
    }

    /**
     * @Route("/project/new", name="tmt_project_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        return array(
            'form' => $this
                ->get('form.factory')
                ->create(new ProjectType())
                ->createView());
    }

    /**
     * @Route("/project/create", name="tmt_project_create")
     * @Method("POST")
     * @Template("TmtProjectBundle:Project:new.html.twig")
     */
    public function createAction() {
        $entity  = new Project();

        $form = $this->createForm(new ProjectType(), $entity);
        $form->bind($this->get('request'));

        if ($form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tmt_project_index'));
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/project/show/{projectId}", name="tmt_project_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($projectId) {
        return array('projectId'=> $projectId);
    }

}
