<?php

namespace Tmt\TestCaseBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tmt\ProjectBundle\Event\ProjectEvent;
use Tmt\TestCaseBundle\Entity\Testcase as TestcaseEntity;

class ProjectListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onProjectDuplicate(ProjectEvent $event)
    {
        $currentProject = $event->getCurrentProject();
        $currentTestcaseService = $this->container->get('tmt.testcase');
        $currentTestcaseService->setProjectId($currentProject->getId());

        $newProject = $event->getNewProject();
        $newTestcaseService = $this->container->get('tmt.testcase');
        $newTestcaseService->setProjectId($newProject->getId());

        foreach ($currentTestcaseService->getAll() as $testcase) {
            $newTestcase = new TestcaseEntity();
            $newTestcase->setProjectId($newProject->getId());

            $newTestcase->setPrecondition($testcase->getPrecondition());
            $newTestcase->setPreconditionId($testcase->getPreconditionId());
            $newTestcase->setPostcondition($testcase->getPostcondition());
            $newTestcase->setPostconditionId($testcase->getPostconditionId());
            $newTestcase->setVersion($testcase->getVersion());
            $newTestcase->setTitle($testcase->getTitle());
            $newTestcase->setDescription($testcase->getDescription());
            $newTestcase->setData($testcase->getData());

            $newTestcase->setLastUser(null);
            $newTestcase->setLastDate(null);
            $newTestcase->setLastState(null);
            $newTestcase->setLastError(null);

            $newTestcaseService->save($newTestcase);
            unset($newTestcase);
        }
    }
}
