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

    public function getAll() {
        return $this->repo
            ->createQueryBuilder('m')
            ->where($this->qb->expr()->eq('m.projectId', ':projectId'))
            ->setParameter('projectId', $this->projectId)
            ->getQuery()
            ->getResult();
    }

    public function create() {
        return $this->saveEntity(new TestcaseEntity());
    }

    public function update($testcaseId) {
        return $this->saveEntity($this->get($testcaseId));
    }

    private function saveEntity($testcase) {
        $data = $this->container->get('request')->request->get('testcase');

        // normalize data
        if (is_array($data['data'])) {
            $newData = array();

            for($i=0; $i<count($data['data']); $i=$i+2)
                if (isset($data['data'][$i]))
                    $newData[] = array($data['data'][$i], $data['data'][$i+1]);

            $data['data'] = $newData;
            unset($newData);
        }

        $testcase->setProjectId($this->projectId);
        $testcase->setVersion(new \DateTime());
        $testcase->setTitle($data['title']);
        $testcase->setDescription($data['description']);
        $testcase->setPrecondition($data['precondition']);
        $testcase->setPreconditionId($data['preconditionId']);
        $testcase->setPostcondition($data['postcondition']);
        $testcase->setPostconditionId($data['postconditionId']);
        $testcase->setData(json_encode($data['data']));

        $validator = $this->container->get('validator');
        $errors = $validator->validate($testcase);

        if (count($errors) === 0)
            $this->save($testcase);

        return $errors;
    }
}