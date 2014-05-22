<?php

namespace Tmt\ProjectBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class ProjectControllerTest extends WebTestCase
{
    private $client = null;

    public function __construct()
    {
        $this->client = static::createClient();
        $session = $this->client->getContainer()->get('session');
        $firewall = 'secured_area';
        $session->set('_security_'.$firewall, serialize(
            new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'))
        ));
        $session->save();
        $this->client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );
        // die($this->client->getResponse()->getContent());
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Projete")')->count());
    }

    public function testNew()
    {
        $crawler = $this->client->request('GET', '/project/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $crawler = $this->client->submit(
            $crawler->selectButton('Speichern')->form(array(
                'project[name]'     => 'PROJECT-NEW',
                'project[template]' => '',
            ))
        );
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("PROJECT-NEW")')->count());
    }

    public function testEdit()
    {
        $crawler = $this->client->request('GET', '/');
        $crawler = $this->client->click($crawler->selectLink('PROJECT-NEW')->link());
        $crawler = $this->client->click($crawler->selectLink('bearbeiten')->link());
        $crawler = $this->client->submit(
            $crawler->selectButton('Speichern')->form(array(
                'project[name]'     => 'PROJECT-EDIT',
                'project[template]' => '',
            ))
        );
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("PROJECT-EDIT")')->count());
    }


    public function testDelete()
    {
        $crawler = $this->client->request('GET', '/');
        $crawler = $this->client->click($crawler->selectLink('PROJECT-EDIT')->link());
        $crawler = $this->client->click($crawler->selectLink('löschen')->link());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Projekt wirklich löschen?")')->count());
        $crawler = $this->client->submit(
            $crawler->selectButton('ja')->form()
        );
        $crawler = $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
