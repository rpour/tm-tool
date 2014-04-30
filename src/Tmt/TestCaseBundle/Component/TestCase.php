<?php

namespace Tmt\TestCaseBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Tmt\CoreBundle\Component\EntityManipulation;
use Tmt\TestCaseBundle\Entity\Testcase as TestcaseEntity;

class TestCase extends EntityManipulation {
    protected $repository = 'TmtTestCaseBundle:Testcase';
    protected $projectId;

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    public function setProjectId() {
        $this->projectId = $this->container->get('request')->get('projectId');
    }

    public function create() {
        return $this->saveEntity(new TestcaseEntity());
    }

    public function update($testcaseId) {
        return $this->saveEntity($this->get($testcaseId));
    }

    private function saveEntity($testcase) {
        $data = $this->container->get('request')->request->get('testcase');

        $testcase->setProjectId($this->projectId);
        $testcase->setVersion(new \DateTime());
        $testcase->setName($data['name']);
        $testcase->setDescription($data['description']);
        $testcase->setPrecondition($data['precondition']);
        $testcase->setPreconditionId($data['preconditionId']);
        $testcase->setPostcondition($data['postcondition']);
        $testcase->setPostconditionId($data['postconditionId']);
        $testcase->setLasttest('');
        $testcase->setData(json_encode($data['data']));

        $validator = $this->container->get('validator');
        $errors = $validator->validate($testcase);

        if (count($errors) === 0)
            $this->save($testcase);

        return $errors;
    }
}