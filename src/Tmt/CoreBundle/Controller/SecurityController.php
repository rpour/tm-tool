<?php

namespace Tmt\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller {
    /**
     *
     * @Route("/login/check", name="tmt_login_check")
     */
    public function loginCheckAction() {}

    /**
     *
     * @Route("/logout", name="tmt_logout")
     */
    public function logoutAction() {}
}