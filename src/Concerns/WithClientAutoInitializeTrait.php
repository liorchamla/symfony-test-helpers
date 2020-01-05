<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait WithClientAutoInitializeTrait
{
    protected function setUp(): void
    {
        if (\method_exists($this, 'initializeClient')) {
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
