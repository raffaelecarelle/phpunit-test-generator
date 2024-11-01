<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Configuration;

final class ConfigurationBuilder
{
    private string $autoloadingStrategy = '';

    private string $sourceNamespace = '';

    private string $sourceDir = '';

    private string $testsNamespace = '';

    private string $testsDir = '';

    public function setAutoloadingStrategy(string $autoloadingStrategy): self
    {
        $this->autoloadingStrategy = $autoloadingStrategy;

        return $this;
    }

    public function setSourceNamespace(string $sourceNamespace): self
    {
        $this->sourceNamespace = $sourceNamespace;

        return $this;
    }

    public function setSourceDir(string $sourceDir): self
    {
        $this->sourceDir = $sourceDir;

        return $this;
    }

    public function setTestsNamespace(string $testsNamespace): self
    {
        $this->testsNamespace = $testsNamespace;

        return $this;
    }

    public function setTestsDir(string $testsDir): self
    {
        $this->testsDir = $testsDir;

        return $this;
    }

    public function build(): Configuration
    {
        return new Configuration(
            $this->autoloadingStrategy,
            $this->sourceNamespace,
            $this->sourceDir,
            $this->testsNamespace,
            $this->testsDir,
        );
    }
}
