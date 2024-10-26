<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator\Tests\Configuration;

use JWage\PHPUnitTestGenerator\Configuration\AutoloadingStrategy;
use JWage\PHPUnitTestGenerator\Configuration\ComposerConfigurationReader;
use Override;
use PHPUnit\Framework\TestCase;

class ComposerConfigurationReaderTest extends TestCase
{
    private ComposerConfigurationReader $composerConfigurationReader;

    public function testCreateConfiguration(): void
    {
        $rootDir = \realpath(__DIR__ . '/../..');
        \assert(\is_string($rootDir));

        $configuration = $this->composerConfigurationReader->createConfiguration($rootDir);

        self::assertSame(AutoloadingStrategy::PSR4, $configuration->getAutoloadingStrategy());
        self::assertSame('JWage\PHPUnitTestGenerator', $configuration->getSourceNamespace());
        self::assertSame(\realpath(__DIR__ . '/../../lib'), $configuration->getSourceDir());
        self::assertSame('JWage\PHPUnitTestGenerator\Tests', $configuration->getTestsNamespace());
        self::assertSame(\realpath(__DIR__ . '/../../tests'), $configuration->getTestsDir());
    }

    #[Override]
    protected function setUp(): void
    {
        $this->composerConfigurationReader = new ComposerConfigurationReader();
    }
}
