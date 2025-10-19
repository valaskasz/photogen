<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class getgentaskTest extends WebTestCase
{
    public function testgetgentast(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(\App\Entity\User::class)->findOneByUsername('valaskasz');
        $client->loginUser($user);

        //Use getgentask to get json response
        $client->request('POST', '/getgentask');
        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);
        foreach ($responseData as $task) {
            $this->assertArrayHasKey('id', $task);
            $this->assertArrayHasKey('dcreated', $task);
            $this->assertArrayHasKey('promptpositive', $task);
            $this->assertArrayHasKey('promptnegative', $task);
            $this->assertArrayHasKey('resolution', $task);
            $this->assertArrayHasKey('priority', $task);
            $this->assertArrayHasKey('modelname', $task);
        }
        


        $this->assertResponseIsSuccessful();
    }
}
