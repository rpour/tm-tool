<?php

namespace Tmt\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tmt\CoreBundle\Component\Application;

class ApplicationEvent extends Event {
    protected $application;
    protected $config;

    public function __construct(Application $application, $config) {
        $this->application = $application;
        $this->config = $config;
    }

    public function getApplication() {
        return $this->application;
    }

    public function getConfig() {
        return $this->config;
    }
}
