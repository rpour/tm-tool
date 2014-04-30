<?php

namespace Tmt\TestCaseBundle\EventListener;

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
        $testcaseId = $this->container->get('request')->get('testcaseId', 0);

        if ($projectId) {
            $projectService = $this->container->get('tmt.project');
            $project = $projectService->get($projectId);
        }

        if ($testcaseId) {
            $testService = $this->container->get('tmt.testcase');
            $testcase = $testService->get($testcaseId);
        }

        if ($application->routeIs('tmt_project_show')) {
            // tmt-menu
            $application
                ->add('tmt-menu', 'testcase', array(
                    'raw' => '<li class="title">Testfälle</li>'
                ))
                ->add('tmt-menu', 'testcase.new', array(
                    'path'  => 'tmt_testcase_new',
                    'param' => array('projectId' => $project->getId()),
                    'label' => 'neu'
                ));

        } else if ($application->bundleIs('testcase')) {

            // home > project > [name] > testcase
            $application->add('breadcrumb', 'projectx', array(
                'path'  => 'tmt_project_show',
                'param' => array('projectId' => $project->getId()),
                'label' => $project->getName()
            ))->add('breadcrumb', 'testcase', array(
                'path'  => 'tmt_testcase_index',
                'param' => array('projectId' => $project->getId()),
                'label' => 'Testfall'
            ));

            // home > project > [name] > testcase > [name]
            if ($application->controllerIs('test')) {
                $application->add('breadcrumb', 'test.run', array(
                    'path'  => 'tmt_test_run',
                    'param' => array(
                        'projectId' => $project->getId(),
                        'testcaseId' => $testcase->getId()
                    ),
                    'label' => $testcase->getName()
                ));
            }

            // home > project > [name] > testcase > new
            if ($application->routeIs('tmt_testcase_new')) {
                $application->add('breadcrumb', 'testcase.new', array(
                    'path'  => 'tmt_test_case_new',
                    'param' => array('projectId' => $project->getId()),
                    'label' => 'new'
                ));
            }
        }
    }
}