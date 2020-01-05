<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait WithClientAutoInitializeTrait
{
    protected function setUp(): void
    {
        if ($this instanceof WithClientTrait) {
            $this->initializeClient();
        }
    }

    protected function tearDown(): void
    {
        if (isset($this->client)) {
            $this->client = null;
        }

        if ($this instanceof KernelTestCase) {
            static::ensureKernelShutdown();
        }
    }
}
