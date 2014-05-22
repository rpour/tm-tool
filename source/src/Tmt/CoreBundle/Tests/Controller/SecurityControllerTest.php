<?php

namespace Tmt\CoreBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/login')
        );
        $crawler = $client->followRedirect();
        $crawler = $client->submit(
            $crawler->selectButton('login')->form(array(
                '_username' => 'admin',
                '_password' => 'test',
            ))
        );
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/')
        );
        $crawler = $client->followRedirect();
        $this->assertEquals(
            1,
            $crawler->filter('div.login:contains("admin")')->count()
        );
    }

    public function testLogout()
    {
        $client = static::createClient();
        $session = $client->getContainer()->get('session');
        $firewall = 'secured_area';
        $session->set('_security_'.$firewall, serialize(
            new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'))
        ));
        $session->save();
        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );
        $crawler = $client->request('GET', '/logout');
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/')
        );
        $crawler = $client->followRedirect();
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/login')
        );
    }
}
