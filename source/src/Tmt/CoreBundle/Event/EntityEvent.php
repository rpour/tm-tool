<?php

namespace Tmt\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class EntityEvent extends Event {
    protected $entity;

    public function __construct($entity) {
        $this->entity = $entity;
    }

    public function getEntity() {
        return $this->entity;
    }
}
