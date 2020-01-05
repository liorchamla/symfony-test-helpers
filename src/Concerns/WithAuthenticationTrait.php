<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

trait WithAuthenticationTrait
{
    /**
     * Shortcut for an admin authentication
     *
     * @param KernelBrowser $client
     * @param string|UserInterface $user Can take a random string username or a valid UserInterface instance
     * @param string $firewallName
     *
     * @return void
     */
    public function authenticateAsAdmin(KernelBrowser $client, $user, string $firewallName = 'main'): void
    {
        $roles = ['ROLE_ADMIN'];

        if ($user instanceof UserInterface) {
            $roles = \array_unique(array_merge($user->getRoles(), $roles));
        }

        $this->authenticate($client, $user, $firewallName, $roles);
    }

    /**
     * Authenticate as a user
     *
     * @param KernelBrowser $client
     * @param string|UserInterface $user A string or a valid UserInterface instance
     * @param string $firewallName
     * @param array $roles
     *
     * @return void
     */
    public function authenticate(
        KernelBrowser $client,
        $user,
        string $firewallName = 'main',
        array $roles = []
    ): void {

        if (\is_string($user)) {
            $roles[] = 'ROLE_USER';
            $roles = \array_unique($roles);
        }

        if ($user instanceof UserInterface) {
            $roles = $user->getRoles();
        }

        $session = $client->getContainer()->get('session');

        $token = new UsernamePasswordToken($user, null, $firewallName, $roles);
        $session->set('_security_' . $firewallName, \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
