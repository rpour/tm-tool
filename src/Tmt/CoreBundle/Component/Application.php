<?php

namespace Tmt\CoreBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Tmt\CoreBundle\Event\ApplicationEvent;

class Application {
    private $container;
    private $data;
    private $dataEnd;
    private $bundle;
    private $controller;
    private $action;
    private $route;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;

        // get controller information
        $controllerInfo = $container->get('request')->get('_controller');

        $this->bundle     = $this->parse("/\\\([\w]*)Bundle\\\/i", $controllerInfo);
        $this->controller = $this->parse("/Controller\\\([\w]*)Controller/i", $controllerInfo);
        $this->action     = $this->parse("/\:([\w]*)Action/i", $controllerInfo);
        $this->route      = $container->get('request')->get('_route');
        unset($controllerInfo);

        $this->data = array();
        $this->dataEnd = array();

        // dispatch event
        $event = new ApplicationEvent($this, array());
        $container->get('event_dispatcher')->dispatch('application.integration', $event);
    }

    public function add($type, $key, $data, $role = null) {
        if (!is_null($role) && false === $this->container->get('security.context')->isGranted($role))
            return $this;

        if (isset($data['path']) && $data['path'] === $this->route)
            $data['action_act'] = true;

        $this->data[$type][$key] = $data;
        return $this;
    }

    public function append($type, $key, $data, $role = null) {
        if (!is_null($role) && false === $this->container->get('security.context')->isGranted($role))
            return $this;

        if (isset($data['path']) && $data['path'] === $this->route)
            $data['action_act'] = true;

        $this->dataEnd[$type][$key] = $data;
        return $this;
    }

    public function get($type, $key = null) {
        $merged = array_merge_recursive($this->data, $this->dataEnd);

        if (is_null($key))
            return isset($merged[$type]) ? $merged[$type] : array();

        return isset($merged[$type][$key]) ? $merged[$type][$key] : '';
    }

    public function bundleIs($bundle) {
        return ($this->bundle === $bundle);
    }

    public function controllerIs($controller) {
        return ($this->controller === $controller);
    }

    public function actionIs($action) {
        return ($this->action === $action);
    }

    public function routeIs($route) {
        return ($this->route === $route);
    }

    private function parse($regex, $string) {
        $matches = array();

        preg_match($regex, $string, $matches);

        return isset($matches[1]) ? strtolower($matches[1]) : '';
    }
}