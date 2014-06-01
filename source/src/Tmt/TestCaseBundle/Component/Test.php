<?php

namespace Tmt\TestCaseBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Tmt\CoreBundle\Component\EntityManipulation;
use Tmt\CoreBundle\Event\EntityEvent;
use Tmt\TestCaseBundle\Entity\Test as TestEntity;

class Test extends EntityManipulation
{
    protected $repository = 'TmtTestCaseBundle:Test';
    protected $testcaseId;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function setTestCaseId($testcaseId = null)
    {
        $this->testcaseId = is_null($testcaseId)
            ? $this->container->get('request')->get('testcaseId', 0)
            : $testcaseId;
    }

    public function getAll()
    {
        return $this->repo
            ->createQueryBuilder('m')
            ->where($this->qb->expr()->eq('m.testcaseId', ':testcaseId'))
            ->setParameter('testcaseId', $this->testcaseId)
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getPassedCount($testcaseId = null)
    {
        if (!is_null($testcaseId)) {
            $this->testcaseId = $testcaseId;
        }

        $passed = (int)$this->repo
            ->createQueryBuilder('m')
            ->select('count(m)')
            ->where(
                $this->qb->expr()->andx(
                    $this->qb->expr()->eq('m.testcaseId', ':testcaseId'),
                    $this->qb->expr()->eq('m.passed', $this->qb->expr()->literal(true))
                )
            )
            ->setParameter('testcaseId', $this->testcaseId)
            ->getQuery()
            ->getSingleScalarResult();

        $notpassed = (int)$this->repo
            ->createQueryBuilder('m')
            ->select('count(m)')
            ->where(
                $this->qb->expr()->andx(
                    $this->qb->expr()->eq('m.testcaseId', ':testcaseId'),
                    $this->qb->expr()->eq('m.passed', $this->qb->expr()->literal(false))
                )
            )
            ->setParameter('testcaseId', $this->testcaseId)
            ->getQuery()
            ->getSingleScalarResult();


        return array(
            'passed' => $passed,
            'notpassed' => $notpassed
        );
    }

    public function create($projectId)
    {
        $result = array();
        $testcaseService = $this->container->get('tmt.testcase');
        $testcaseService->setProjectId($projectId);

        $testcaseEntity = $testcaseService->get($this->testcaseId);
        $testcaseData = json_decode($testcaseEntity->getData());

        $data = $this->container->get('request')->request->get('testcase');

        // check for errors.
        $testcaseErrors = 0;
        $passed = 0;

        for ($i=0; $i<count($data); $i++) {
            $testcaseData[$i]['passed'] = 0;

            if ($data[$i+1]['passed']) {
                $testcaseData[$i]['passed'] = 1;
                $passed++;

                if (trim($data[$i+1]['error']) != "") {
                    $testcaseErrors++;
                    $testcaseData[$i]['error'] = trim($data[$i+1]['error']);
                }
            }
        }

        $result['errors'] = array("Nicht alles ausgefüllt!");

        if ($testcaseErrors || $passed === count($data)) {
            $date = $testcaseEntity->getVersion();
            $version = $date->format('y.mdHi');

            unset($data);
            $data['steps']       = $testcaseData;
            $data['projectId']   = $testcaseEntity->getProjectId();
            $data['version']     = $version;
            $data['title']       = $testcaseEntity->getTitle();
            $data['description'] = $testcaseEntity->getDescription();

            $test = new TestEntity();
            $test->setTestCaseId((int)$this->testcaseId);
            $test->setDate(new \DateTime());
            $test->setData(json_encode($data));
            $test->setPassed((boolean)!$testcaseErrors);
            $test->setUsername($this->getUsername());
            $test->setUserAgent($this->container->get('request')->headers->get('User-Agent'));

            $validator = $this->container->get('validator');
            $result['errors'] = $validator->validate($test);
        }

        $result['testcase'] = $testcaseEntity;

        if (count($result['errors']) === 0) {
            $this->save($test);

            // Update testcase
            $testcaseEntity->setLastUser($this->getUsername());
            $testcaseEntity->setLastDate(new \DateTime());
            $testcaseEntity->setLastState((boolean)!$testcaseErrors);
            $testcaseService->save($testcaseEntity);

        }

        return $result;
    }

    public function removeByTestcaseId($testcaseId)
    {
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
