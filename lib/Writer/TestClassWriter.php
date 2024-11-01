<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Writer;

use PHPUnitTestGenerator\GeneratedTestClass;

interface TestClassWriter
{
    public function write(GeneratedTestClass $generatedTestClass): string;
}
