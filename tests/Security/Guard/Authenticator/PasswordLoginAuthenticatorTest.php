<?php

namespace App\Tests\Security\Guard\Authenticator;


use App\Entity\LoginUser;
use App\Security\Guard\Authenticator\PasswordLoginAuthenticator;
use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\UseCase\Login;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PasswordLoginAuthenticatorTest extends TestCase
{
    private $router;

    private $login;

    /**
     * @var string
     */
    private $loginPath;

    /**
     * @var string
     */
    private $loginSuccessPath;

    protected function setUp()
    {
        $this->router = $this->prophesize(RouterInterface::class);
        $this->login = $this->prophesize(Login::class);
        $this->loginPath = 'app_auth_login';
        $this->loginSuccessPath = 'app_top';
    }

    public function testGetCredentials()
    {
        $username = 'user-1';
        $password = 'pass-word';

        $request = new Request();
        $request->request = new ParameterBag([
            '_username' => $username,
            '_password' => $password,
        ]);

        $authenticator = $this->getAuthenticator();
        $result = $authenticator->getCredentials($request);

        $this->assertSame([
            'username' => $username,
            'password' => $password,
        ], $result);

    }

    public function testGetUser()
    {
        $username = 'user-1';
        $password = 'pass-word';

        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        $provider = $this->prophesize(UserProviderInterface::class);
        $provider->loadUserByUsername($username)
            ->willReturn(new LoginUser(new Account($username, 'test@test.com', $password)));

        $authenticator = $this->getAuthenticator();
        $authenticator->getUser($credentials, $provider->reveal());

        $provider->loadUserByUsername($username)
            ->shouldHaveBeenCalled();

    }

    public function testCheckCredentials()
    {
        $user = $this->prophesize(LoginUser::class);
        $username = 'user-1';
        $password = 'pass-word';

        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        $account = new Account($username, 'test@test.com', $password);

        $user->getAccount()
            ->willReturn($account);

        $this->login->password($account, $password)
            ->willReturn(true);

        $authenticator = $this->getAuthenticator();
        $authenticator->checkCredentials($credentials, $user->reveal());

        $this->login->password($account, $password)
            ->shouldHaveBeenCalled();

    }

    public function testOnAuthenticationSuccess()
    {
        $request = $this->prophesize(Request::class);
        $token = $this->prophesize(TokenInterface::class);
        $providerKey = 'account-provider';

        $this->router->generate($this->loginSuccessPath)
            ->willReturn("/");

        $authenticator = $this->getAuthenticator();
        $result = $authenticator->onAuthenticationSuccess($request->reveal(), $token->reveal(), $providerKey);
        $this->assertInstanceOf(RedirectResponse::class, $result);

        $this->router->generate($this->loginSuccessPath)
            ->shouldHaveBeenCalled();
    }

    public function testOnAuthenticationFailure()
    {
        $loginPath = '/auth/login';
        $exception = new AuthenticationException();

        $request = $this->prophesize(Request::class);
        $request->hasSession()
            ->willReturn(false);

        $this->router->generate($this->loginPath)
            ->willReturn($loginPath);

        $authenticator = $this->getAuthenticator();
        $authenticator->onAuthenticationFailure($request->reveal(), $exception);

        $this->router->generate($this->loginPath)
            ->shouldHaveBeenCalled();
    }


    /**
     * @dataProvider dataSupports
     *
     * @param $requestPath
     * @param $method
     * @param $expectResult
     */
    public function testSupports($requestPath, $method, $expectResult)
    {

        $request = $this->prophesize(Request::class);
        $request->getPathInfo()
            ->willReturn($requestPath);

        $request->getMethod()
            ->willReturn($method);

        $this->router->generate($this->loginPath)
            ->willReturn($requestPath);

        $authenticator = $this->getAuthenticator();
        $result = $authenticator->supports($request->reveal());

        $request->getPathInfo()
            ->shouldHaveBeenCalled();

        $request->getMethod()
            ->shouldHaveBeenCalled();

        $this->assertSame($expectResult, $result);

    }

    public function dataSupports()
    {
        return [
            ['/auth/login','POST', true],
            ['/auth/login','GET', false]
        ];
    }

    private function getAuthenticator() : PasswordLoginAuthenticator
    {
        return new PasswordLoginAuthenticator($this->router->reveal(), $this->login->reveal(), $this->loginPath, $this->loginSuccessPath);
    }

}