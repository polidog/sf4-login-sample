<?php

namespace App\Tests\Entity;


use App\Entity\LoginUser;
use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;

class LoginUserTest extends TestCase
{
    private $account;

    protected function setUp()
    {
        $this->account = $this->prophesize(Account::class);
    }

    public function testGetId()
    {
        $this->account->getId()
            ->willReturn(1);
        $user = $this->getLoginUser();
        $user->getId();

        $this->account->getId()
            ->shouldHaveBeenCalled();
    }

    public function testGetRoles()
    {
        $user = $this->getLoginUser();
        $this->assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testGetPassword()
    {
        $this->account->getPassword()
            ->willReturn(md5(time()));

        $user = $this->getLoginUser();
        $user->getPassword();

        $this->account->getPassword()
            ->shouldHaveBeenCalled();

    }

    public function testGetUsername()
    {
        $this->account->getEmail()
            ->willReturn('test@test.com');

        $user = $this->getLoginUser();
        $user->getUsername();

        $this->account->getEmail()
            ->shouldHaveBeenCalled();
    }

    public function getAccount()
    {
        $user = $this->getLoginUser();
        $account = $user->getAccount();

        $this->assertInstanceOf(Account::class, $account);
    }


    private function getLoginUser()
    {
        return new LoginUser($this->account->reveal());
    }

}