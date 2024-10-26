<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator\Configuration;

final readonly class Configuration
{
    public function __construct(
        private string $autoloadingStrategy,
        private string $sourceNamespace,
        private string $sourceDir,
        private string $testsNamespace,
        private string $testsDir,
    ) {}

    public function getAutoloadingStrategy(): string
    {
        return $this->autoloadingStrategy;
    }

    public function getSourceNamespace(): string
    {
        return $this->sourceNamespace;
    }

    public function getSourceDir(): string
    {
        return $this->sourceDir;
    }

    public function getTestsNamespace(): string
    {
        return $this->testsNamespace;
    }

    public function getTestsDir(): string
    {
        return $this->testsDir;
    }
}
