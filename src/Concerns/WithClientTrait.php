<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait WithClientTrait
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * Initialize a client
     *
     * @param callable|null $clientCreationFn Give a callable to build your own client
     *
     * @return void
     */
    public function initializeClient(?callable $clientCreationFn = null): void
    {
        // If we get a callback for client construction
        if ($clientCreationFn) {
            $this->client = $clientCreationFn();
        }

        // Simple construction
        $this->client = static::createClient();
    }
}
