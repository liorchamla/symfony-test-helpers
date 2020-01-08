<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Liior\SymfonyTestHelpers\Exception\ClientNotCreatedException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

trait WithClientTrait
{
    /** @var AbstractBrowser */
    protected $client;

    /**
     * Assert response contains specified string
     *
     * @param string $string
     * @return self
     */
    public function assertSee(string $string): self
    {
        $this->ensureClientInitialized();

        $this->assertStringContainsString($string, $this->client->getResponse()->getContent());

        return $this;
    }

    /**
     * Assert response does not contain specified string
     *
     * @param string $string
     * @return self
     */
    public function assertNotSee(string $string): self
    {
        $this->ensureClientInitialized();

        $this->assertNotContains($string, $this->client->getResponse()->getContent());

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

    /**
     * Send GET request
     *
     * @param string $url
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @param boolean $changeHistory
     * @return void
     */
    protected function get(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('GET', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Send POST request
     *
     * @param string $url
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @param boolean $changeHistory
     * @return void
     */
    protected function post(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('POST', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Send DELETE request
     *
     * @param string $url
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @param boolean $changeHistory
     * @return void
     */
    protected function delete(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('DELETE', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Send PUT request
     *
     * @param string $url
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @param boolean $changeHistory
     * @return void
     */
    protected function put(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('PUT', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Send PATCH request
     *
     * @param string $url
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @param boolean $changeHistory
     * @return void
     */
    protected function patch(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        $this->ensureClientInitialized();

        return $this->client->request('PATCH', $url, $parameters, $files, $server, $content, $changeHistory);
    }
}
