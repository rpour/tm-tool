<?php

namespace Tmt\TestCaseBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tmt\CoreBundle\Event\EntityEvent;


class EntityListener {
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onEntityRemove(EntityEvent $event) {
        $entity = $event->getEntity();

        if (get_class($entity) === 'Tmt\ProjectBundle\Entity\Project') {
            $testcaseService = $this->container->get('tmt.testcase');
            $testcaseService->removeByProjectId($entity->getId());

        } else if(get_class($entity) === 'Tmt\TestCaseBundle\Entity\Testcase') {
            $testService = $this->container->get('tmt.test');
            $testService->removeByTestcaseId($entity->getId());
        }
    }
}