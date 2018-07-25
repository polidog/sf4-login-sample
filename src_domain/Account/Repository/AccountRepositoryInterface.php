<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Repository;

use Polidog\LoginSample\Account\Entity\Account;

interface AccountRepositoryInterface
{
    public function add(Account $account);

    public function findEmail(string $email);
}
