<?php

namespace Polidog\LoginSample\Tests\Account\Specification;


use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;
use Polidog\LoginSample\Account\Specification\DuplicateEmailSpec;

class DuplicateEmailSpecTest extends TestCase
{
    private $repository;

    protected function setUp()
    {
        $this->repository = $this->prophesize(AccountRepositoryInterface::class);
    }


    public function testIsSatisfiedBy()
    {
        $email = 'test@test.com';

        $account = $this->prophesize(Account::class);
        $account->getEmail()
            ->willReturn($email);

        $this->repository->findEmail($email)
            ->willReturn(null);

        $spec = new DuplicateEmailSpec($this->repository->reveal());
        $result = $spec->isSatisfiedBy($account->reveal());

        $this->assertTrue($result);

        $this->repository->findEmail($email)
            ->shouldHaveBeenCalled();

        $account->getEmail()
            ->shouldHaveBeenCalled();
    }
}