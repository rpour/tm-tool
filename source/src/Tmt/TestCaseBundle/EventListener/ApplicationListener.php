<?php

namespace Tmt\TestCaseBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tmt\CoreBundle\Event\ApplicationEvent;


class ApplicationListener {
    protected $container;
    protected $application;
    protected $testcaseId;
    protected $project;
    protected $testcase;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onIntegration(ApplicationEvent $event) {
        $this->application = $event->getApplication();
        $this->testcaseId = $this->container->get('request')->get('testcaseId', 0);

        $projectId = $this->container->get('request')->get('projectId', 0);
        if ($projectId) {
            $projectService = $this->container->get('tmt.project');
            $this->project = $projectService->get($projectId);
        }

        $this->buildBreadcrumb();
        $this->buildMenuBar();

    }

    private function buildBreadcrumb() {
        /***********************************************************************
         * BREADCRUMB
         **********************************************************************/

        // home > project > [name] > testcase
        if ($this->application->bundleIs('testcase') || $this->testcaseId) {
            $this->application->add('breadcrumb', 'testcase', array(
                'path'  => 'tmt_testcase_index',
                'param' => array('projectId' => $this->project->getId()),
                'label' => 'Testfall'
            ));
        }

        // home > project > [name] > testcase > [name]
        if ($this->testcaseId) {
            $testService = $this->container->get('tmt.testcase');
            $this->testcase = $testService->get($this->testcaseId);

            $this->application->add('breadcrumb', 'test.run', array(
                'path'  => 'tmt_test_run',
                'param' => array(
                    'projectId' => $this->project->getId(),
                    'testcaseId' => $this->testcase->getId()
                ),
                'label' => $this->testcase->getTitle()
            ));
        }

        // home > project > [name] > testcase > new
        if ($this->application->routeIs('tmt_testcase_new')) {
            $this->application->add('breadcrumb', 'testcase.new', array(
                'path'  => 'tmt_test_case_new',
                'param' => array('projectId' => $this->project->getId()),
                'label' => 'new'
            ));
        }

    }

    private function buildMenuBar() {
        /***********************************************************************
         * MENU-BAR
         **********************************************************************/
        if ($this->application->routeIs('tmt_testcase_index')) {
            $this->application
                ->add('tmt-menubar-label', 'label', 'Testfälle')
                ->add('tmt-menubar', 'testcase.pdf', array(
                    'path'  => 'tmt_testcase_pdf',
                    'param' => array('projectId' => $this->project->getId()),
                    'label' => 'PDF',
                    'class' => 'icon-book'
                ), 'ROLE_TESTCASE_ADMIN')
                ->add('tmt-menubar', 'testcase.new', array(
                    'path'  => 'tmt_testcase_new',
                    'param' => array('projectId' => $this->project->getId()),
                    'label' => 'neu',
                    'class' => 'icon-file-o'
                ), 'ROLE_TESTCASE_ADMIN');

        } else if ($this->application->routeIs('tmt_test_index')) {
            $this->application
                ->add('tmt-menubar-label', 'label', $this->testcase->getTitle())
                ->add('tmt-menubar', 'testcase.run', array(
                    'path'  => 'tmt_test_run',
                    'param' =>array(
                        'projectId' => $this->project->getId(),
                        'testcaseId' => $this->testcase->getId()
                    ),
                    'label' => 'ausführen',
                    'class' => 'icon-sign-in'
                ), 'ROLE_TESTCASE_USER')
                ->add('tmt-menubar', 'testcase.back', array(
                    'path'  => 'tmt_testcase_index',
                    'param' => array('projectId' => $this->project->getId()),
                    'label' => 'zurück',
                    'class' => 'icon-mail-reply'
                ));

        } else if ($this->application->routeIs('tmt_testcase_new')) {
            $this->application
                ->add('tmt-menubar-label', 'label', 'Neuer Testfall');

        } else if ($this->application->routeIs('tmt_testcase_edit')) {
            $this->application
                ->add('tmt-menubar-label', 'label', 'Testfall bearbeiten')
                ->add('tmt-menubar', 'testcase.remove', array(
                    'path'  => 'tmt_testcase_confirm',
                    'param' => array(
                        'projectId' => $this->project->getId(),
                        'testcaseId' => $this->testcase->getId()
                    ),
                    'label' => 'löschen',
                    'class' => 'icon-trash-o'
                ));

        } else if ($this->application->routeIs('tmt_test_run')) {
            $this->application
                ->add('tmt-menubar-label', 'label', 'Test: ' . $this->testcase->getTitle());
        }


        if ($this->application->bundleIs('testcase') && !$this->application->routeIs('tmt_testcase_index')) {
            $this->application
                ->add('tmt-menubar', 'testcase.back', array(
                    'path'  => 'tmt_testcase_index',
                    'param' => array('projectId' => $this->project->getId()),
                    'label' => 'zurück',
                    'class' => 'icon-mail-reply'
                ));
        }
    }
}