<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Liior\SymfonyTestHelpers\Exception\ClientNotCreatedException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait WithHttpMethodsTrait
{
    use WithClientTrait;

    protected function get(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new ClientNotCreatedException('You can\'t use WithClientTrait\'s functions without calling a first time `$this->initializeClient()` !');
        }

        return $this->client->request('GET', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function post(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new ClientNotCreatedException('You can\'t use WithClientTrait\'s functions without calling a first time `$this->initializeClient()` !');
        }

        return $this->client->request('POST', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function delete(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new ClientNotCreatedException('You can\'t use WithClientTrait\'s functions without calling a first time `$this->initializeClient()` !');
        }

        return $this->client->request('DELETE', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function put(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new ClientNotCreatedException('You can\'t use WithClientTrait\'s functions without calling a first time `$this->initializeClient()` !');
        }

        return $this->client->request('PUT', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function patch(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new ClientNotCreatedException('You can\'t use WithClientTrait\'s functions without calling a first time `$this->initializeClient()` !');
        }

        return $this->client->request('PATCH', $url, $parameters, $files, $server, $content, $changeHistory);
    }
}
