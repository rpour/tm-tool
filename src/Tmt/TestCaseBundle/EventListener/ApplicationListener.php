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

        /***********************************************************************
         * BREADCRUMB
         **********************************************************************/

        // home > project > [name] > testcase
        if ($application->bundleIs('testcase') || $testcaseId) {
            $application->add('breadcrumb', 'testcase', array(
                'path'  => 'tmt_testcase_index',
                'param' => array('projectId' => $project->getId()),
                'label' => 'Testfall'
            ));
        }

        // home > project > [name] > testcase > [name]
        if ($testcaseId) {
            $testService = $this->container->get('tmt.testcase');
            $testcase = $testService->get($testcaseId);

            $application->add('breadcrumb', 'test.run', array(
                'path'  => 'tmt_test_run',
                'param' => array(
                    'projectId' => $project->getId(),
                    'testcaseId' => $testcase->getId()
                ),
                'label' => $testcase->getTitle()
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

        /***********************************************************************
         * MENU-BAR
         **********************************************************************/
        if ($application->routeIs('tmt_testcase_index')) {
            $application
                ->add('tmt-menubar-label', 'label', 'Testfälle')
                ->add('tmt-menubar', 'testcase.new', array(
                    'path'  => 'tmt_testcase_new',
                    'param' => array('projectId' => $project->getId()),
                    'label' => 'neu',
                    'class' => 'icon-file-o'
                ), 'ROLE_TESTCASE_ADMIN');

        } else if ($application->routeIs('tmt_test_index')) {
            $application
                ->add('tmt-menubar-label', 'label', $testcase->getTitle())
                ->add('tmt-menubar', 'testcase.run', array(
                    'path'  => 'tmt_test_run',
                    'param' =>array(
                        'projectId' => $project->getId(),
                        'testcaseId' => $testcase->getId()
                    ),
                    'label' => 'ausführen',
                    'class' => 'icon-sign-in'
                ), 'ROLE_TESTCASE_USER')
                ->add('tmt-menubar', 'testcase.back', array(
                    'path'  => 'tmt_testcase_index',
                    'param' => array('projectId' => $project->getId()),
                    'label' => 'zurück',
                    'class' => 'icon-mail-reply'
                ));

        } else if ($application->routeIs('tmt_testcase_new')) {
            $application
                ->add('tmt-menubar-label', 'label', 'Neuer Testfall');

        } else if ($application->routeIs('tmt_testcase_edit')) {
            $application
                ->add('tmt-menubar-label', 'label', 'Testfall bearbeiten')
                ->add('tmt-menubar', 'testcase.remove', array(
                    'path'  => 'tmt_testcase_confirm',
                    'param' => array(
                        'projectId' => $project->getId(),
                        'testcaseId' => $testcase->getId()
                    ),
                    'label' => 'löschen',
                    'class' => 'icon-trash-o'
                ));

        } else if ($application->routeIs('tmt_test_run')) {
            $application
                ->add('tmt-menubar-label', 'label', 'Test: ' . $testcase->getTitle());
        }


        if ($application->bundleIs('testcase') && !$application->routeIs('tmt_testcase_index')) {
            $application
                ->add('tmt-menubar', 'testcase.back', array(
                    'path'  => 'tmt_testcase_index',
                    'param' => array('projectId' => $project->getId()),
                    'label' => 'zurück',
                    'class' => 'icon-mail-reply'
                ));
        }
    }
}