<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Service;

interface PasswordEncoderInterface
{
    /**
     * @param string $plainPassword
     *
     * @return string
     */
    public function encode(string $plainPassword): string;
}
