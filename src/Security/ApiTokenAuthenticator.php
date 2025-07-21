<?php
namespace App\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
private string $validToken;

public function __construct(string $validToken = 'super-secret-token') {
$this->validToken = $validToken;
}

public function supports(Request $request): ?bool {
return str_starts_with($request->headers->get('Authorization', ''), 'Bearer ');
}

public function authenticate(Request $request): SelfValidatingPassport {
$authHeader = $request->headers->get('Authorization');
$token = str_replace('Bearer ', '', $authHeader);

if ($token !== $this->validToken) {
throw new AuthenticationException('Invalid token');
}

return new SelfValidatingPassport(new UserBadge('api-user'));
}

public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse {
return null;
}

public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse {
return new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
}
}
