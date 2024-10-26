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

    public function testCreatePsr0Configuration(): void
    {
        $rootDir = \realpath(__DIR__ . '/psr0');
        \assert(\is_string($rootDir));

        $configuration = $this->composerConfigurationReader->createConfiguration($rootDir);

        self::assertSame(AutoloadingStrategy::PSR0, $configuration->getAutoloadingStrategy());
        self::assertSame('Psr0\Config', $configuration->getSourceNamespace());
        self::assertSame(\realpath(__DIR__ . '/psr0'), $configuration->getSourceDir());
        self::assertSame('Psr0\Config\Tests', $configuration->getTestsNamespace());
        self::assertSame(\realpath(__DIR__ . '/psr0'), $configuration->getTestsDir());
    }

    public function testCreatePsr4Configuration(): void
    {
        $rootDir = \realpath(__DIR__ . '/psr4');
        \assert(\is_string($rootDir));

        $configuration = $this->composerConfigurationReader->createConfiguration($rootDir);

        self::assertSame(AutoloadingStrategy::PSR4, $configuration->getAutoloadingStrategy());
        self::assertSame('Psr4\Config', $configuration->getSourceNamespace());
        self::assertSame(\realpath(__DIR__ . '/psr4'), $configuration->getSourceDir());
        self::assertSame('Psr4\Config\Tests', $configuration->getTestsNamespace());
        self::assertSame(\realpath(__DIR__ . '/psr4'), $configuration->getTestsDir());
    }

    #[Override]
    protected function setUp(): void
    {
        $this->composerConfigurationReader = new ComposerConfigurationReader();
    }
}
