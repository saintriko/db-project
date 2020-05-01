<?php


namespace App\Security;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class BasicAuthenticator extends AbstractGuardAuthenticator
{
    private $repo;

    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function getCredentials(Request $request)
    {
        return [
            'BasicAuthValue' => $request->headers->get('Authorization'),
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return object|UserInterface|void|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['BasicAuthValue'];

        if (null === $token) {
            return;
        }

        $isTokenValid = preg_match("/^Basic\s(.*?)$/", $credentials['BasicAuthValue'], $matches);

        if (!$isTokenValid) {
            throw new AuthenticationException('Unexpected header value');
        }

        $explodedLoginAndPassword = explode(':', base64_decode($matches[1]));

        if (count($explodedLoginAndPassword) !== 2) {
            throw new AuthenticationException('User password and login has unsupported characters');
        }

        if ($u = $this->repo->findByNickname($explodedLoginAndPassword[0])) {
            if ($u->doesPlainStringMatchPassword($explodedLoginAndPassword[1])) {
                return new UserModelAdapter($u);
            }
        }

        return null;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => $exception->getMessage(),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return true;
    }
}