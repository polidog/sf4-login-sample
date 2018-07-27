<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Service;

interface PasswordValidInterface
{
    public function valid($raw, $encoded): bool;
}
