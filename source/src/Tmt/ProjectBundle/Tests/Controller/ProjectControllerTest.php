<?php

namespace Tmt\ProjectBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class ProjectControllerTest extends WebTestCase
{
    // die($client->getResponse()->getContent());
    // die((string)$client->getResponse()->getStatusCode());

    public function testIndex()
    {
        $client = $this->login('guest', array('ROLE_USER'));
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Projete")')->count());
    }

    public function testNew()
    {
        // login as guest
        $client = $this->login('guest', array('ROLE_USER'));

        // 403 Forbidden for user
        $crawler = $client->request('GET', '/project/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        $client = $this->login('admin', array('ROLE_PROJECT_ADMIN'));
        $crawler = $client->request('GET', '/project/new');
        $this->assertTrue($client->getResponse()->isSuccessful());

        // fill out the form and send
        $crawler = $client->submit(
            $crawler->selectButton('Speichern')->form(array(
                'project[name]'     => 'PROJECT-NEW',
                'project[template]' => '',
            ))
        );
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("PROJECT-NEW")')->count());
    }

    public function testEdit()
    {
        $client = $this->login('admin', array('ROLE_PROJECT_ADMIN'));
        $crawler = $client->request('GET', '/');
        $crawler = $client->click($crawler->selectLink('PROJECT-NEW')->link());
        $crawler = $client->click($crawler->selectLink('bearbeiten')->link());
        $crawler = $client->submit(
            $crawler->selectButton('Speichern')->form(array(
                'project[name]'     => 'PROJECT-EDIT',
                'project[template]' => '',
            ))
        );
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("PROJECT-EDIT")')->count());
    }

    public function testDelete()
    {
        $client = $this->login('admin', array('ROLE_PROJECT_ADMIN'));
        $crawler = $client->request('GET', '/');
        $crawler = $client->click($crawler->selectLink('PROJECT-EDIT')->link());
        $crawler = $client->click($crawler->selectLink('löschen')->link());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Projekt wirklich löschen?")')->count());
        $crawler = $client->submit(
            $crawler->selectButton('ja')->form()
        );
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function login($username, $roles)
    {
        $client = static::createClient();

        $session = $client->getContainer()->get('session');
        $session->set('_security_secured_area', serialize(
            new UsernamePasswordToken($username, null, 'secured_area', $roles)
        ));
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        return $client;
    }
}
