<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

trait SessionHelper
{
    public function generateCsrfToken(KernelBrowser $client, string $tokenId): string
    {
        $session = $this->getSession($client);
        $container = static::getContainer();
        $tokenGenerator = $container->get('security.csrf.token_generator');
        $csrfToken = $tokenGenerator->generateToken();
        $session->set(SessionTokenStorage::SESSION_NAMESPACE . "/{$tokenId}", $csrfToken);
        $session->save();

        return $csrfToken;
    }

    public function getSession(KernelBrowser $client): Session
    {
        $cookie = $client->getCookieJar()
            ->get('MOCKSESSID');

        // create a new session object
        $container = static::getContainer();
        $session = $container->get('session.factory')
            ->createSession();
        if ($cookie instanceof Cookie) {
            // get the session id from the session cookie if it exists
            $session->setId($cookie->getValue());
            $session->start();
        } else {
            // or create a new session id and a session cookie
            $session->start();
            $session->save();

            $sessionCookie = new Cookie($session->getName(), $session->getId(), null, null, 'localhost');
            $client->getCookieJar()
                ->set($sessionCookie);
        }

        return $session;
    }

    public function createSession(KernelBrowser $client): Session
    {
        $container = $client->getContainer();
        $sessionSavePath = $container->getParameter('session.save_path');
        $sessionStorage = new MockFileSessionStorage($sessionSavePath);

        $session = new Session($sessionStorage);
        $session->start();
        $session->save();

        $sessionCookie = new Cookie($session->getName(), $session->getId(), null, null, 'localhost');
        $client->getCookieJar()
            ->set($sessionCookie);

        return $session;
    }
}
