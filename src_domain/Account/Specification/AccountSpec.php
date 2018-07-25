<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Specification;

use Polidog\LoginSample\Account\Entity\Account;

interface AccountSpec
{
    public function isSatisfied(Account $account): bool;
}
