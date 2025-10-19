<?php

namespace App\Tests\Controller;

use App\Entity\Genrequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GeneratePhotoControllerTest extends WebTestCase
{
    public function testRecordPhotoRequest(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(\App\Entity\User::class)->findOneBy(['username' => 'valaskasz']);

        if (!$user) {
            $user = new \App\Entity\User();
            $user->setUsername('valaskasz');
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT)); // Set a default password
            $user->setRoles(['ROLE_USER']);
            $user->setEmail('valaskasz@localhost');
            $entityManager->persist($user);
            $entityManager->flush();
        }

        $client->loginUser($user);
        $payload = [
            'promptpositive' => 'A realistic cat',
            'promptnegative' => 'blurry',
            'resolution' => '1024x1024',
            'priority' => 5,
            'modelname' => 'model-v1'
        ];
        $client->request('POST', '/generatephotorow', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
        $this->assertResponseIsSuccessful();
        $responseContent = $client->getResponse()->getContent();
        $this->assertNotNull($responseContent, 'Response content is null. Check if the endpoint is returning a valid response.');
        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData, 'Response data is not a valid JSON array.');
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals('A realistic cat', $responseData['promptpositive']);
        $this->assertEquals('blurry', $responseData['promptnegative']);
        $this->assertEquals('1024x1024', $responseData['resolution']);
        $this->assertEquals(5, $responseData['priority']);
    
          // Ellenőrizzük, hogy tényleg bekerült az adatbázisba
          $container = static::getContainer();
          $em = $container->get(EntityManagerInterface::class);
          $saved = $em->getRepository(Genrequest::class)->find($responseData['id']);
  
          $this->assertNotNull($saved);
          $this->assertEquals('A realistic cat', $saved->getPromtpositive());


    }
}
