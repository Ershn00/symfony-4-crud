<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends WebTestCase
{
    use MailerAssertionsTrait;

    public function testMailIsSentAndContentIsOk()
    {
        $client = $this->createClient();
        $client->request('GET', 'http://localhost:8000/colleague/sendgreeting/First%20Name/first@test.com');
        $this->assertResponseIsSuccessful();

        $this->assertEmailCount(1);

    }
}