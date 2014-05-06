<?php

namespace Tmt\TestCaseBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Tmt\CoreBundle\Component\EntityManipulation;
use Tmt\CoreBundle\Event\EntityEvent;
use Tmt\TestCaseBundle\Entity\Test as TestEntity;

class Test extends EntityManipulation {
    protected $repository = 'TmtTestCaseBundle:Test';
    protected $testcaseId;

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    public function setTestCaseId($testcaseId = null) {
        $this->testcaseId = is_null($testcaseId)
            ? $this->container->get('request')->get('testcaseId', 0)
            : $testcaseId;
    }

    public function getAll() {
        return $this->repo
            ->createQueryBuilder('m')
            ->where($this->qb->expr()->eq('m.testcaseId', ':testcaseId'))
            ->setParameter('testcaseId', $this->testcaseId)
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getPassedCount($testcaseId = null) {
        if (!is_null($testcaseId))
            $this->testcaseId = $testcaseId;

        $passed = (int)$this->repo
            ->createQueryBuilder('m')
            ->select('count(m)')
            ->where(
                $this->qb->expr()->andx(
                    $this->qb->expr()->eq('m.testcaseId', ':testcaseId'),
                    $this->qb->expr()->eq('m.passed', ':passed')
                )
            )
            ->setParameter('testcaseId', $this->testcaseId)
            ->setParameter('passed', true)
            ->getQuery()
            ->getSingleScalarResult();

        $notpassed = (int)$this->repo
            ->createQueryBuilder('m')
            ->select('count(m)')
            ->where(
                $this->qb->expr()->andx(
                    $this->qb->expr()->eq('m.testcaseId', ':testcaseId'),
                    $this->qb->expr()->eq('m.passed', ':passed')
                )
            )
            ->setParameter('testcaseId', $this->testcaseId)
            ->setParameter('passed', false)
            ->getQuery()
            ->getSingleScalarResult();

        return array(
            'passed' => $passed,
            'notpassed' => $notpassed
        );
    }

    public function create() {
        $result = array();
        $testcaseService = $this->container->get('tmt.testcase');
        $testcaseEntity = $testcaseService->get($this->testcaseId);
        $testcaseData = json_decode($testcaseEntity->getData());

        $data = $this->container->get('request')->request->get('testcase');

        // check for errors.
        $testcaseErrors = 0;
        $passed = 0;

        for($i=0; $i<count($data);$i++) {
            if ($data[$i+1]['passed']) {
                $testcaseData[$i]['passed'] = 1;
                $passed++;

                if (trim($data[$i+1]['error']) != "") {
                    $testcaseErrors++;
                    $testcaseData[$i]['error'] = trim($data[$i+1]['error']);
                }
            } else
                $testcaseData[$i]['passed'] = 0;
        }

        if (!$testcaseErrors && $passed !== count($data)) {
            $result['errors'] = array("Nicht alles ausgefüllt!");

        } else {
            $date = $testcaseEntity->getVersion();
            $version = $date->format('y.mdHi');

            unset($data);
            $data['steps']       = $testcaseData;
            $data['projectId']   = $testcaseEntity->getProjectId();
            $data['version']     = $version;
            $data['title']       = $testcaseEntity->getTitle();
            $data['description'] = $testcaseEntity->getDescription();

            $test = new TestEntity();
            $test->setTestCaseId($this->testcaseId);
            $test->setDate(new \DateTime());
            $test->setData(json_encode($data));
            $test->setPassed(!$testcaseErrors);
            $test->setUser($this->getUsername());

            $validator = $this->container->get('validator');
            $result['errors'] = $validator->validate($test);
        }

        if (count($result['errors']) === 0) {
            $this->save($test);

            // Update testcase
            $testcaseEntity->setLastUser($this->getUsername());
            $testcaseEntity->setLastDate(new \DateTime());
            $testcaseService->save($testcaseEntity);

        } else {
            $testcaseEntity->setData(json_encode($testcaseData));
            $result['testcase'] = $testcaseEntity;
        }

        return $result;
    }

    public function removeByTestcaseId($testcaseId) {
        $tests = $this->repo
            ->createQueryBuilder('m')
            ->where($this->qb->expr()->eq('m.testcaseId', ':testcaseId'))
            ->setParameter('testcaseId', $testcaseId)
            ->getQuery()
            ->getResult();

        foreach ($tests as $test) {
            // dispatch event
            $this->container->get('event_dispatcher')->dispatch(
                'entity.remove',
                new EntityEvent($test)
            );

            $this->em->remove($test);
        }

        $this->em->flush();
    }
}