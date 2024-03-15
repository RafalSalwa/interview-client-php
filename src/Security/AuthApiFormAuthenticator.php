<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\AuthApiClient;
use App\Client\UsersApiClient;
use App\Exception\AuthenticationExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

final class AuthApiFormAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private readonly AuthApiClient $authApiClient,
        private readonly UsersApiClient $usersApiClient,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly LoggerInterface $logger,
    ) {}

    public function authenticate(Request $request): Passport
    {
        if (false === $request->request->has('email') || false === $request->request->has('password')) {
            throw new InvalidArgumentException('Missing authentication parameters in request.');
        }

        try {
            $tokenPair = $this->authApiClient->signIn(
                $request->request->get('email'),
                $request->request->get('password'),
            );
            $user = $this->usersApiClient->loadUserByIdentifier($tokenPair->getToken()->value());

            return new SelfValidatingPassport(
                userBadge: new UserBadge($user->getUserIdentifier()),
            );
        } catch (AuthenticationExceptionInterface $authException) {
            $this->logger->critical($authException->getMessage());
            $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $request->request->get('email'));

            throw new AuthenticationException($authException->getMessage(), $authException->getCode(), $authException);
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if (true === $request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->getLoginUrl($request));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('login_index');
    }
}