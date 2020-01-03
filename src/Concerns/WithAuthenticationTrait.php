<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait WithAuthenticationTrait
{
    public function authenticateAsAdmin(KernelBrowser $client, string $firewallName = 'main', string $username = 'example'): void
    {
        $this->authenticate($client, $firewallName, ['ROLE_ADMIN'], $username);
    }

    public function authenticate(
        KernelBrowser $client,
        string $firewallName = 'main',
        array $roles = [],
        string $username = 'example'
    ): void
    {
        $roles[] = 'ROLE_USER';
        $roles = \array_unique($roles);

        $session = $client->getContainer()->get('session');

        $token = new UsernamePasswordToken($username, null, $firewallName, $roles);
        $session->set('_security_' . $firewallName, \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
