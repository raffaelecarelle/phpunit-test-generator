<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Configuration;

final class AutoloadingStrategy
{
    public const string PSR4 = 'psr4';

    public const string PSR0 = 'psr0';

    private function __construct() {}
}
