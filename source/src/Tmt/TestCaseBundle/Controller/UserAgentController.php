<?php

namespace Tmt\TestCaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Tmt\TestCaseBundle\Entity\UserAgent as UserAgentEntity;

/**
 * @Route("/project/{projectId}/useragent")
 */
class UserAgentController extends Controller
{

    /**
     * @Route("/{hash}", name="tmt_useragent_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($projectId, $hash)
    {
        return array(
            'projectId' => $projectId,
            'hash'      => $hash
        );
    }

    /**
     * @Route("/", name="tmt_useragent_create")
     * @Method("POST")
     */
    public function createAction()
    {
        $userAgentService = $this->get('tmt.useragent');

        $entity = $userAgentService->get(
            $this->container->get('request')->request->get('hash', null)
        );

        if (is_null($entity)) {
            $entity = new UserAgentEntity();
        }

        $json                   = array();
        $json['os']             = $this->container->get('request')->request->get('os');
        $json['osVersion']      = $this->container->get('request')->request->get('osVersion');
        $json['browser']        = $this->container->get('request')->request->get('browser');
        $json['browserVersion'] = $this->container->get('request')->request->get('browserVersion');

        if (strtolower($json['os']) === 'ubuntu') {
            $json['iconOs'] = 'tux';
        } elseif (strtolower($json['os']) === 'mac os') {
            $json['iconOs'] = 'apple';
        } elseif (strtolower($json['os']) === 'windows' && (int)$json['osVersion'] === 8) {
            $json['iconOs'] = 'windows8';
        } elseif (strtolower($json['os']) === 'windows') {
            $json['iconOs'] = 'windows';
        }

        if (strtolower($json['browser']) === 'chrome') {
            $json['iconBrowser'] = 'chrome';
        } elseif (strtolower($json['browser']) === 'firefox') {
            $json['iconBrowser'] = 'firefox';
        } elseif (strtolower($json['browser']) === 'internet explorer') {
            $json['iconBrowser'] = 'IE';
        } elseif (strtolower($json['browser']) === 'opera') {
            $json['iconBrowser'] = 'opera';
        } elseif (strtolower($json['browser']) === 'safari') {
            $json['iconBrowser'] = 'safari';
        }

        $entity->setId($this->container->get('request')->request->get('hash'));
        $entity->setJson(json_encode($json));

        $userAgentService->save($entity);

        return $userAgentService->redirect('tmt_project_show', array(
            'projectId' => $this->container->get('request')->request->get('projectId')
        ));
    }
}
