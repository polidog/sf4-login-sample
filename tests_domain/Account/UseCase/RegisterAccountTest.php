<?php

namespace Polidog\LoginSample\Tests\Account\UseCase;


use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;
use Polidog\LoginSample\Account\Specification\DuplicateEmailSpec;
use Polidog\LoginSample\Account\UseCase\RegisterAccount;
use Polidog\LoginSample\TransactionManagerInterface;

class RegisterAccountTest extends TestCase
{
    private $specification;

    private $repository;

    private $passwordEncoder;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->specification = $this->prophesize(DuplicateEmailSpec::class);
        $this->repository = $this->prophesize(AccountRepositoryInterface::class);
        $this->passwordEncoder = $this->prophesize(PasswordEncoderInterface::class);
    }

    public function testRun()
    {
        $account = $this->prophesize(Account::class);

        $this->specification->isSatisfied($account)
            ->willReturn(true);

        $useCase = new RegisterAccount($this->specification->reveal(), $this->repository->reveal(), new TestTransactionManager(), $this->passwordEncoder->reveal());
        $useCase->run($account->reveal());

        $this->specification->isSatisfied($account)
            ->shouldHaveBeenCalled();

        $account->encode($this->passwordEncoder)
            ->shouldHaveBeenCalled();

        $this->repository->add($account)
            ->shouldHaveBeenCalled();
    }

    /**
     * @expectedException Polidog\LoginSample\Account\Exception\DuplicateEmailException
     */
    public function testSpecException()
    {
        $account = $this->prophesize(Account::class);

        $this->specification->isSatisfied($account)
            ->willReturn(false);

        $account->getEmail()
            ->willReturn("test@test.com");

        $useCase = new RegisterAccount($this->specification->reveal(), $this->repository->reveal(), new TestTransactionManager(), $this->passwordEncoder->reveal());
        $useCase->run($account->reveal());

    }

}

class TestTransactionManager implements TransactionManagerInterface
{
    public function begin(): void
    {
        // TODO: Implement begin() method.
    }

    public function commit(): void
    {
        // TODO: Implement commit() method.
    }

    public function rollback(): void
    {
        // TODO: Implement rollback() method.
    }

    public function transactional(callable $func): void
    {
        $func($this);
    }

}