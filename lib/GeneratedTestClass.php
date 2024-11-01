<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator;

final readonly class GeneratedTestClass
{
    public function __construct(private string $className, private string $testClassName, private string $code) {}

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getTestClassName(): string
    {
        return $this->testClassName;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
