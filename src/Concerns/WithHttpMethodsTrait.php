<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait WithHttpMethodsTrait
{
    use WithClientTrait;

    protected function get(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new Exception(sprintf('You should not use WithHttpMethods without a %s property setted to a valid KernelBrowser object', '$this->client'));
        }

        return $this->client->request('GET', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function post(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new Exception(sprintf('You should not use WithHttpMethods without a %s property setted to a valid KernelBrowser object', '$this->client'));
        }

        return $this->client->request('POST', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function delete(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new Exception(sprintf('You should not use WithHttpMethods without a %s property setted to a valid KernelBrowser object', '$this->client'));
        }

        return $this->client->request('DELETE', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function put(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new Exception(sprintf('You should not use WithHttpMethods without a %s property setted to a valid KernelBrowser object', '$this->client'));
        }

        return $this->client->request('PUT', $url, $parameters, $files, $server, $content, $changeHistory);
    }

    protected function patch(string $url, array $parameters = [], array $files = [], array $server = [], ?string $content = null, bool $changeHistory = true)
    {
        if (!$this->client || !$this->client instanceof KernelBrowser) {
            throw new Exception(sprintf('You should not use WithHttpMethods without a %s property setted to a valid KernelBrowser object', '$this->client'));
        }

        return $this->client->request('PATCH', $url, $parameters, $files, $server, $content, $changeHistory);
    }
}
