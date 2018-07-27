<?php

declare(strict_types=1);

namespace App\Security\Guard\Authenticator;

use App\Entity\LoginUser;
use Polidog\LoginSample\Account\UseCase\Login;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class PasswordLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Login
     */
    private $login;

    /**
     * @var string
     */
    private $loginPath;

    /**
     * @var string
     */
    private $loginSuccessPath;

    /**
     * PasswordLoginAuthenticator constructor.
     *
     * @param RouterInterface $router
     * @param Login           $login
     * @param string          $loginPath
     * @param string          $loginSuccessPath
     */
    public function __construct(RouterInterface $router, Login $login, string $loginPath, string $loginSuccessPath)
    {
        $this->router = $router;
        $this->login = $login;
        $this->loginPath = $loginPath;
        $this->loginSuccessPath = $loginSuccessPath;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate($this->loginPath);
    }

    public function supports(Request $request)
    {
        $url = $this->router->generate($this->loginPath);
        if ($request->getPathInfo() === $url && 'POST' === $request->getMethod()) {
            return true;
        }

        return false;
    }

    public function getCredentials(Request $request)
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        return [
            'username' => $username,
            'password' => $password,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        assert($user instanceof LoginUser);

        return $this->login->password($user->getAccount(), $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate($this->loginSuccessPath));
    }
}
