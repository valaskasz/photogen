<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class confirmphotorequestTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(\App\Entity\User::class)->findOneBy(['username' => 'valaskasz']);
        $client->loginUser($user);

        $genrequest = new \App\Entity\Genrequest();
        $genrequest->setUser($user);
        $genrequest->setPromtpositive('A test prompt');
        $genrequest->setPromtnegative('negative prompt');
        $genrequest->setResolution('512x512');
        $genrequest->setPriority(1);
        $genrequest->setModelname('test-model');
        $genrequest->setDcreated(new \DateTime());
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($genrequest);
        $entityManager->flush();

        $payload = [
            'id' => $genrequest->getId(),
            'securitytoken' => 'testtoken123'
        ];

        $client->request('POST', '/confirmphotorequest', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));


        $this->assertResponseIsSuccessful();
        $responseContent = $client->getResponse()->getContent();
        $this->assertNotNull($responseContent, 'Response content is null. Check if the endpoint is returning a valid response.');
        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData, 'Response data is not a valid JSON array.');
        $this->assertArrayHasKey('status', $responseData);
        $this->assertEquals('ok', $responseData['status']);
    }
}
