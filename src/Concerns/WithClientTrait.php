<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Liior\SymfonyTestHelpers\Exception\ClientNotCreatedException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

trait WithClientTrait
{
    /** @var AbstractBrowser */
    protected $client;

    public function assertSee(string $string): self
    {
        $this->ensureClientInitialized();

        $this->assertStringContainsString($string, $this->client->getResponse()->getContent());

        return $this;
    }

    public function initializeClient(...$clientArguments): void
    {
        if (!$this instanceof WebTestCase) {
            throw new ClientNotCreatedException(\sprintf(
                'Could not create client. Test case must extend %s.',
                WebTestCase::class
            ));
        }

        $this->client = static::createClient(...$clientArguments);
    }

    public function ensureClientInitialized(): void
    {
        if (!$this->client) {
            $this->initializeClient();
        }
    }

    protected function get(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('GET', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function post(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('POST', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function delete(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('DELETE', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function put(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('PUT', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function patch(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('PATCH', $url, $parameters, $files, $server, $content, $changeHistory);
    }
}
