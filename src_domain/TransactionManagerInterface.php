<?php

declare(strict_types=1);

namespace Polidog\LoginSample;

interface TransactionManagerInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;

    public function transactional(callable $func): void;
}
