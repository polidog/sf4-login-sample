<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Service;

interface PasswordValidInterface
{
    public function valid(string $plainPassword, string $encodedPassword): bool;
}
