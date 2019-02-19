<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * Ce test permet de vérifier que la liste des students s'affiche
     */
    public function testLoginIndexPage()
    {
        // créer un client
        $client = static::createClient();
        // se rendre à l'URL `/login/`
        $crawler = $client->request('GET', '/login');
        // vérifier que le serveur répond un code HTTP 200
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        // vérifier que le texte `Email` se trouve dans la balise `body`
        $this->assertContains('Email', $crawler->filter('body')->text());
        // vérifier que le text `Password``se trouve dans la balise `body`
        $this->assertContains('Password', $crawler->filter('body')->text());
    }
}
