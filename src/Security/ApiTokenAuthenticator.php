<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private string $validToken;

    public function __construct(string $validToken = 'super-secret-token')
    {
        $this->validToken = $validToken;
    }

    public function supports(Request $request): ?bool
    {

        $header = $request->headers->get('Authorization', '');
        error_log('Authorization header in supports(): ' . $header);
        return str_starts_with($header, 'Bearer ');
        // Поддерживаем только запросы с заголовком Authorization: Bearer ...
        return str_starts_with($request->headers->get('Authorization', ''), 'Bearer ');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $authHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);

        if ($token !== $this->validToken) {
            throw new AuthenticationException('Invalid token');
        }

        // Возвращаем паспорт с "пользователем", у которого есть роль ROLE_API
        return new SelfValidatingPassport(new UserBadge('api-user', function(string $userIdentifier) {
            return new class($userIdentifier) implements UserInterface {
                private string $userIdentifier;

                public function __construct(string $userIdentifier)
                {
                    $this->userIdentifier = $userIdentifier;
                }

                public function getRoles(): array
                {
                    return ['ROLE_API'];
                }

                public function getPassword(): ?string
                {
                    return null;
                }

                public function getSalt(): ?string
                {
                    return null;
                }

                public function getUserIdentifier(): string
                {
                    return $this->userIdentifier;
                }

                public function eraseCredentials(): void
                {
                }
            };
        }));
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        // Продолжаем обработку запроса, без редиректов
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
