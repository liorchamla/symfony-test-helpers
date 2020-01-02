<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait WithAuthenticationTrait
{
    use WithDatabaseTrait;

    /**
     * Authenticate as an admin user (ROLE_ADMIN)
     *
     * @param KernelBrowser $client
     * @param string $firewallName
     * @param string $username
     *
     * @return void
     */
    public function authenticateAsAdmin(KernelBrowser $client, string $firewallName = "main", string $username = "example")
    {
        $this->authenticate($client, $firewallName, ["ROLE_ADMIN"], $username);
    }

    /**
     * Authenticate as a user
     *
     * @param KernelBrowser $client
     * @param string $firewallName
     * @param array $roles
     * @param string $username
     *
     * @return void
     */
    public function authenticate(KernelBrowser $client, string $firewallName = "main", array $roles = [], string $username = "example")
    {
        $roles = array_merge(['ROLE_USER'], $roles);

        $session = $client->getContainer()->get('session');

        $token = new UsernamePasswordToken($username, null, $firewallName, $roles);
        $session->set('_security_' . $firewallName, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
