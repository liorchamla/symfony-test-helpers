<?php

namespace Liior\SymfonyTestHelpers;

use Liior\SymfonyTestHelpers\Concerns\WithAuthenticationTrait;
use Liior\SymfonyTestHelpers\Concerns\WithClientTrait;
use Liior\SymfonyTestHelpers\Concerns\WithDatabaseTrait;
use Liior\SymfonyTestHelpers\Concerns\WithFakerTrait;
use Liior\SymfonyTestHelpers\Concerns\WithHttpMethodsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    use WithAuthenticationTrait;
    use WithClientTrait;
    use WithDatabaseTrait;
    use WithFakerTrait;
    use WithHttpMethodsTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->initializeClient();
    }

    public function assertSee(string $string)
    {
        $this->assertStringContainsString($string, $this->client->getResponse()->getContent());

        return $this;
    }
}
