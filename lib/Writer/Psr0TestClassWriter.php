<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator\Writer;

use JWage\PHPUnitTestGenerator\Configuration\Configuration;
use JWage\PHPUnitTestGenerator\GeneratedTestClass;
use Override;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

final readonly class Psr0TestClassWriter implements TestClassWriter
{
    public function __construct(
        private Configuration $configuration,
        private Filesystem $filesystem = new Filesystem(),
    ) {}

    #[Override]
    public function write(GeneratedTestClass $generatedTestClass): string
    {
        $writePath = $this->generatePsr0TestWritePath($generatedTestClass);

        $writeDirectory = \dirname($writePath);

        if ( ! $this->filesystem->exists($writeDirectory)) {
            $this->filesystem->mkdir($writeDirectory, 0777);
        }

        if ($this->filesystem->exists($writePath)) {
            throw new RuntimeException(\sprintf('Test class already exists at %s', $writePath));
        }

        $this->filesystem->dumpFile(
            $writePath,
            $generatedTestClass->getCode(),
        );

        return $writePath;
    }

    private function generatePsr0TestWritePath(GeneratedTestClass $generatedTestClass): string
    {
        $writePath = $this->configuration->getTestsDir();

        $writePath .= '/' . \str_replace(
            $this->configuration->getTestsNamespace() . '\\',
            '',
            $generatedTestClass->getTestClassName(),
        ) . '.php';

        return \str_replace('\\', \DIRECTORY_SEPARATOR, $writePath);
    }
}
