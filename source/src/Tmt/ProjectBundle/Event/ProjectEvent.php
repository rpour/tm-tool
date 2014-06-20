<?php

namespace Tmt\ProjectBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ProjectEvent extends Event
{
    protected $currentProject;
    protected $newProject;

    public function __construct($currentProject, $newProject)
    {
        $this->currentProject = $currentProject;
        $this->newProject = $newProject;
    }

    public function getCurrentProject()
    {
        return $this->currentProject;
    }

    public function getNewProject()
    {
        return $this->newProject;
    }
}
