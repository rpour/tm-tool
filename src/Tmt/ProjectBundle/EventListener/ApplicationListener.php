<?php

namespace Tmt\ProjectBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tmt\CoreBundle\Event\ApplicationEvent;


class ApplicationListener {
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onIntegration(ApplicationEvent $event) {
        $application = $event->getApplication();

        $projectId = $this->container->get('request')->get('projectId', 0);

        /***********************************************************************
         * BREADCRUMB
         **********************************************************************/

        // home > project
        if ($application->bundleIs('project') || $projectId) {
            $application->add('breadcrumb', 'project', array(
                'path'  => 'tmt_project_index',
                'label' => 'Projekt'
            ));
        }

        // home > project > [name]
        if ($projectId) {
            $projectService = $this->container->get('tmt.project');
            $project = $projectService->get($projectId);

            $application->add('breadcrumb', strtolower($project->getName()), array(
                'path'  => 'tmt_project_show',
                'param' => array('projectId' => $project->getId()),
                'label' => $project->getName(),
                'class' => 'breadcrumb-value'
            ));
        }

        // home > project > new
        if ($application->routeIs('tmt_project_new')) {
            $application->add('breadcrumb', 'project.new', array(
                'path'  => 'tmt_project_index',
                'label' => 'new'
            ));
        }

        /***********************************************************************
         * MENU-BAR
         **********************************************************************/
        if ($application->routeIs('tmt_project_index')) {
            $application
            ->add('tmt-menubar-label', 'label', 'Projete')
            ->add('tmt-menubar', 'project.new', array(
                'path'  => 'tmt_project_new',
                'label' => 'neu',
                'class' => 'icon-file-o'
            ), 'ROLE_PROJECT_ADMIN');

        } else if ($application->routeIs('tmt_project_show')) {
            $application
            ->add('tmt-menubar-label', 'label', $project->getName())
            ->add('tmt-menubar', 'project.edit', array(
                'path'  => 'tmt_project_edit',
                'param' => array('projectId' => $project->getId()),
                'label' => 'bearbeiten',
                'class' => 'icon-edit'
            ), 'ROLE_PROJECT_ADMIN')
            ->add('tmt-menubar', 'project.remove', array(
                'path'  => 'tmt_project_confirm',
                'param' => array('projectId' => $project->getId()),
                'label' => 'löschen',
                'class' => 'icon-trash-o'
            ), 'ROLE_PROJECT_ADMIN');
        } else if ($application->routeIs('tmt_project_confirm')) {
            $application
                ->add('tmt-menubar-label', 'label', $project->getName());
        }

        if ($application->bundleIs('project') && !$application->routeIs('tmt_project_index')) {
            $application
                ->add('tmt-menubar', 'testcase.back', array(
                    'path'  => 'tmt_project_index',
                    'label' => 'zurück',
                    'class' => 'icon-mail-reply'
                ));
        }
    }
}
