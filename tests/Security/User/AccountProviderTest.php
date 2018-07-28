<?php

namespace App\Tests\Security\User;


use App\Entity\LoginUser;
use App\Security\User\AccountProvider;
use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Exception\AccountNotFoundException;
use Polidog\LoginSample\Account\UseCase\GetLoginAccount;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountProviderTest extends TestCase
{
    private $getAccount;

    protected function setUp()
    {
        $this->getAccount = $this->prophesize(GetLoginAccount::class);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameFailsIfCantFindAccount()
    {
        $username = "test@test.com";

        $this->getAccount->email($username)
            ->willThrow(new AccountNotFoundException());

        $provider = new AccountProvider($this->getAccount->reveal());
        $provider->loadUserByUsername($username);
    }

    public function testLoadUserByUsername()
    {
        $username = "test@test.com";

        $this->getAccount->email($username)
            ->willReturn(new Account('test', $username, 'test'));

        $provider = new AccountProvider($this->getAccount->reveal());
        $result = $provider->loadUserByUsername($username);

        $this->assertInstanceOf(LoginUser::class, $result);

        $this->getAccount->email($username)
            ->shouldHaveBeenCalled();

    }

    public function testRefreshUser()
    {
        $username = "test@test.com";
        $user = $this->prophesize(UserInterface::class);
        $user->getUsername()
            ->willReturn($username);


        $this->getAccount->email($username)
            ->willReturn(new Account('test', $username, 'test'));

        $provider = new AccountProvider($this->getAccount->reveal());

        $result = $provider->refreshUser($user->reveal());

        $this->assertInstanceOf(LoginUser::class, $result);

        $this->getAccount->email($username)
            ->shouldHaveBeenCalled();

    }

    public function testSupportsClass()
    {
        $provider = new AccountProvider($this->getAccount->reveal());
        $this->assertTrue($provider->supportsClass(new LoginUser(new Account('test','test@test.com','test'))));
    }
}