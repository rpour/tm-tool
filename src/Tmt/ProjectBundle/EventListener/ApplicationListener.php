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
        $projectService = $this->container->get('tmt.project');
        $project = $projectService->get(
            $this->container->get('request')->get('projectId', 0)
        );

        if ($application->routeIs('tmt_project_index')) {

            // home > project
            $application->add('breadcrumb', 'project', array(
                'path'  => 'tmt_project_index',
                'label' => 'Projekt'
            ));

            // add link to new project
            if (!$application->actionIs('new')) {
                $application->add('tmt-menu', 'project.new', array(
                    'path'  => 'tmt_project_new',
                    'label' => 'neu'
                ));
            }

        } else if ($application->bundleIs('project') ||
                   $application->bundleIs('testcase')) {

            if ($application->controllerIs('project')) {
                $application
                    ->append('tmt-menu', 'project', array(
                        'raw' => '<li class="title">Projekt</li>'
                    ))->append('tmt-menu', 'project.new', array(
                        'path'  => 'tmt_project_new',
                        'label' => 'neu'
                    ));

                if (is_object($project)) {
                    $application->append('tmt-menu', 'project.edit', array(
                        'path'  => 'tmt_project_new',
                        'param' => array('projectId' => $project->getId()),
                        'label' => 'bearbeiten'
                    ));
                }
            }

            // home > project
            $application->add('breadcrumb', 'project', array(
                'path'  => 'tmt_project_index',
                'label' => 'Projekt'
            ));

            if ($application->routeIs('tmt_project_new')) {

                // home > project > new
                $application->add('breadcrumb', 'project.new', array(
                    'path'  => 'tmt_project_index',
                    'label' => 'new'
                ));

            } else if ($application->routeIs('tmt_project_show')) {
                if (is_object($project)) {
                    // home > project > [name]
                    $application->add('breadcrumb', strtolower($project->getName()), array(
                        'path'  => 'tmt_core_show',
                        'param' => array('projectId' => $project->getId()),
                        'label' => $project->getName()
                    ));
                }
            }
        }
    }
}
