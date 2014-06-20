<?php

namespace Tmt\ProjectBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tmt\CoreBundle\Event\ApplicationEvent;

class ApplicationListener
{
    protected $container;
    protected $application;
    protected $projectId;
    protected $project;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onIntegration(ApplicationEvent $event)
    {
        $this->application = $event->getApplication();
        $this->projectId = $this->container->get('request')->get('projectId', 0);

        $this->buildBreadcrumb();
        $this->buildMenuBar();
    }

    private function buildBreadcrumb()
    {
        /***********************************************************************
         * BREADCRUMB
         **********************************************************************/

        // home > project
        if ($this->application->bundleIs('project') || $this->projectId) {
            $this->application->add('breadcrumb', 'project', array(
                'path'  => 'tmt_project_index',
                'label' => 'Projekt',
                'class' => 'icon-home'
            ));
        }

        // home > project > [name]
        if ($this->projectId) {
            $projectService = $this->container->get('tmt.project');
            $this->project = $projectService->get($this->projectId);

            $this->application->add('breadcrumb', strtolower($this->project->getName()), array(
                'path'  => 'tmt_project_show',
                'param' => array('projectId' => $this->project->getId()),
                'label' => $this->project->getName(),
                'class' => 'breadcrumb-highlight'
            ));
        }

        // home > project > new
        if ($this->application->routeIs('tmt_project_new')) {
            $this->application->add('breadcrumb', 'project.new', array(
                'path'  => 'tmt_project_index',
                'label' => 'new'
            ));
        }
    }

    private function buildMenuBar()
    {
        /***********************************************************************
         * MENU-BAR
         **********************************************************************/
        if ($this->application->routeIs('tmt_project_index')) {
            $this->application
            ->add('tmt-menubar-label', 'label', 'Projete')
            ->add('tmt-menubar', 'project.new', array(
                'path'  => 'tmt_project_new',
                'label' => 'neu',
                'class' => 'icon-file-o'
            ), 'ROLE_PROJECT_ADMIN');

        } elseif ($this->application->routeIs('tmt_project_show')) {
            $this->application
            ->add('tmt-menubar-label', 'label', $this->project->getName())
            ->add('tmt-menubar', 'project.duplicate', array(
                'path'  => 'tmt_project_duplicate',
                'param' => array('projectId' => $this->project->getId()),
                'label' => 'duplizieren',
                'class' => 'icon-copy'
            ), 'ROLE_PROJECT_ADMIN')
            ->add('tmt-menubar', 'project.edit', array(
                'path'  => 'tmt_project_edit',
                'param' => array('projectId' => $this->project->getId()),
                'label' => 'bearbeiten',
                'class' => 'icon-edit'
            ), 'ROLE_PROJECT_ADMIN')
            ->add('tmt-menubar', 'project.remove', array(
                'path'  => 'tmt_project_confirm',
                'param' => array('projectId' => $this->project->getId()),
                'label' => 'löschen',
                'class' => 'icon-trash-o'
            ), 'ROLE_PROJECT_ADMIN');
        } elseif ($this->application->routeIs('tmt_project_confirm')) {
            $this->application
                ->add('tmt-menubar-label', 'label', $this->project->getName());
        }

        if ($this->application->bundleIs('project') && !$this->application->routeIs('tmt_project_index')) {
            $this->application
                ->add('tmt-menubar', 'testcase.back', array(
                    'path'  => 'tmt_project_index',
                    'label' => 'zurück',
                    'class' => 'icon-mail-reply'
                ));
        }
    }
}
