<?php

namespace Tmt\ProjectBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class Project {
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function getAll() {
        return $this->container->get('doctrine')
            ->getManager()
            ->getRepository('TmtProjectBundle:Project')
            ->findAll();
    }

    public function get($id) {
        return $this->container->get('doctrine')
            ->getManager()
            ->getRepository('TmtProjectBundle:Project')
            ->findOneById($id);
    }

    public function update($entity) {
        $em = $this->container->get('doctrine')->getManager();
        $em->persist($entity);
        $em->flush();
    }

    public function delete() {

    }
}