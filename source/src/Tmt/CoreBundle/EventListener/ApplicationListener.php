<?php

namespace Tmt\CoreBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tmt\CoreBundle\Event\ApplicationEvent;


class ApplicationListener {
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onIntegration(ApplicationEvent $event) {
        $application = $event->getApplication();
        $application->add('breadcrumb', 'home', array(
            'path'  => 'tmt_project_index',
            'label' => 'Home',
            'class' => 'icon-home'
        ));
    }
}