<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Liior\SymfonyTestHelpers\Exception\ClientNotCreatedException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

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
    }
}
