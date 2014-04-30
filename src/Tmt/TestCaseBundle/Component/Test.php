<?php

namespace Tmt\TestCaseBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Tmt\CoreBundle\Component\EntityManipulation;
use Tmt\TestCaseBundle\Entity\Test as TestEntity;

class Test extends EntityManipulation {
    protected $repository = 'TmtTestCaseBundle:Test';
    protected $testcaseId;

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    public function setTestCaseId() {
        $this->testcaseId = $this->container->get('request')->get('testcaseId');
    }
}