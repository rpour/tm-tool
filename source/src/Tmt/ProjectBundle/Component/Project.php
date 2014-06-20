<?php

namespace Tmt\ProjectBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Tmt\CoreBundle\Component\EntityManipulation;
use Tmt\ProjectBundle\Entity\Project as ProjectEntity;
use Tmt\ProjectBundle\Event\ProjectEvent;

class Project extends EntityManipulation
{
    protected $repository = 'TmtProjectBundle:Project';

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function duplicate($projectId)
    {
        $currentEntity = $this->get($projectId);

        if (is_null($currentEntity)) {
            throw new \Exception("Project not found!", 1);
        }

        $newEntity = new ProjectEntity();
        $newEntity->setName(
            $currentEntity->getName() . ' (copy)'
        );
        $newEntity->setTemplate(
            $currentEntity->getTemplate()
        );

        $this->save($newEntity);

        // dispatch event
        $this->container->get('event_dispatcher')->dispatch(
            'project.duplicate',
            new ProjectEvent($currentEntity, $newEntity)
        );
    }
}
