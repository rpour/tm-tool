<?php

namespace Tmt\CoreBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class EntityManipulation {
    protected $container;
    protected $em;
    protected $repo;
    protected $qb;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
        $this->repo = $this->em->getRepository($this->repository);
        $this->qb = $this->em->createQueryBuilder();
    }

    public function get($id) {
        return $this->repo->findOneById($id);
    }

    public function getAll() {
        return $this->repo->findAll();
    }

    public function save($entity) {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function remove($entity) {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function getUsername() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        return $user->getUsername();
    }
}