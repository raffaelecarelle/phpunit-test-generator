<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator\Configuration;

use RuntimeException;

final class ComposerConfigurationReader
{
    private string $projectRoot;

    public function createConfiguration(?string $path = null): Configuration
    {
        $this->projectRoot = $path ?? \getcwd();

        $composerJsonPath = $this->projectRoot . '/composer.json';

        if ( ! \file_exists($composerJsonPath)) {
            throw new RuntimeException(
                'Could not find composer.json in the current working directory',
            );
        }

        $json = \file_get_contents($composerJsonPath);

        if ($json === false) {
            throw new RuntimeException(
                'Could not read composer.json',
            );
        }

        $composerJsonData = \json_decode($json, true);

        if ($this->isPsr4($composerJsonData)) {
            return $this->getPsr4Configuration($composerJsonData);
        }

        if ($this->isPsr0($composerJsonData)) {
            return $this->getPsr0Configuration($composerJsonData);
        }

        throw new RuntimeException('Only psr4 and psr0 is currently supported. Pull Requests accepted to support other autoloading standards.');
    }

    /**
     * @param mixed[] $composerJsonData
     */
    private function getPsr4Configuration(array $composerJsonData): Configuration
    {
        [$sourceNamespace, $sourceDir] = $this->getPsr4Source($composerJsonData);
        [$testsNamespace, $testsDir] = $this->getPsr4Tests($composerJsonData);

        return (new ConfigurationBuilder())
            ->setAutoloadingStrategy(AutoloadingStrategy::PSR4)
            ->setSourceNamespace(\rtrim($sourceNamespace, '\\'))
            ->setSourceDir(\rtrim($sourceDir, '/'))
            ->setTestsNamespace(\rtrim($testsNamespace, '\\'))
            ->setTestsDir(\rtrim($testsDir, '/'))
            ->build();
    }

    /**
     * @param mixed[] $composerJsonData
     */
    private function getPsr0Configuration(array $composerJsonData): Configuration
    {
        [$sourceNamespace, $sourceDir] = $this->getPsr0Source($composerJsonData);
        [$testsNamespace, $testsDir] = $this->getPsr0Tests($composerJsonData);

        return (new ConfigurationBuilder())
            ->setAutoloadingStrategy(AutoloadingStrategy::PSR0)
            ->setSourceNamespace(\rtrim($sourceNamespace, '\\'))
            ->setSourceDir(\rtrim($sourceDir, '/'))
            ->setTestsNamespace(\rtrim($testsNamespace, '\\'))
            ->setTestsDir(\rtrim($testsDir, '/'))
            ->build();
    }

    /**
     * @param mixed[] $composerJsonData
     */
    private function isPsr4(array $composerJsonData): bool
    {
        return isset($composerJsonData['autoload']['psr-4'])
            && isset($composerJsonData['autoload-dev']['psr-4']);
    }

    /**
     * @param mixed[] $composerJsonData
     */
    private function isPsr0(array $composerJsonData): bool
    {
        return isset($composerJsonData['autoload']['psr-0'])
            && isset($composerJsonData['autoload-dev']['psr-0']);
    }

    /**
     * @param mixed[] $composerJsonData
     *
     * @return string[]
     */
    private function getPsr4Source(array $composerJsonData): array
    {
        return $this->getNamespaceSourcePair($composerJsonData['autoload']['psr-4']);
    }

    /**
     * @param mixed[] $composerJsonData
     *
     * @return string[]
     */
    private function getPsr0Source(array $composerJsonData): array
    {
        return $this->getNamespaceSourcePair($composerJsonData['autoload']['psr-0']);
    }

    /**
     * @param mixed[] $composerJsonData
     *
     * @return string[]
     */
    private function getPsr4Tests(array $composerJsonData): array
    {
        return $this->getNamespaceSourcePair($composerJsonData['autoload-dev']['psr-4']);
    }

    /**
     * @param mixed[] $composerJsonData
     *
     * @return string[]
     */
    private function getPsr0Tests(array $composerJsonData): array
    {
        return $this->getNamespaceSourcePair($composerJsonData['autoload-dev']['psr-0']);
    }

    /**
     * @param string[] $psr
     *
     * @return string[]
     */
    private function getNamespaceSourcePair(array $psr): array
    {
        $sourceNamespace = \key($psr);
        \assert(\is_string($sourceNamespace));

        $sourceDir = $this->projectRoot . '/' . $psr[$sourceNamespace];

        return [$sourceNamespace, $sourceDir];
    }
}
