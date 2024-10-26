<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator\Command;

use InvalidArgumentException;
use JWage\PHPUnitTestGenerator\Configuration\AutoloadingStrategy;
use JWage\PHPUnitTestGenerator\Configuration\ComposerConfigurationReader;
use JWage\PHPUnitTestGenerator\Configuration\Configuration;
use JWage\PHPUnitTestGenerator\TestClassGenerator;
use JWage\PHPUnitTestGenerator\Writer\Psr0TestClassWriter;
use JWage\PHPUnitTestGenerator\Writer\Psr4TestClassWriter;
use JWage\PHPUnitTestGenerator\Writer\TestClassWriter;
use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateTestClassCommand extends Command
{
    #[Override]
    protected function configure(): void
    {
        $this
            ->setName('generate-test-class')
            ->setDescription('Generate a PHPUnit test class from a class.')
            ->addArgument('class', InputArgument::OPTIONAL, 'The class name to generate the test for.');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('class');

        if ($className === null || $className === '') {
            throw new InvalidArgumentException('Specify class name to generate a unit test for.');
        }

        \assert(\is_string($className));

        $className = $this->getClassName($className);

        $output->writeln(\sprintf('Generating test class for <info>%s</info>', $className));
        $output->writeln('');

        $configuration = $this->createConfiguration();

        $generateTestClass = $this->createTestClassGenerator($configuration);

        $generatedTestClass = $generateTestClass->generate($className);

        $output->writeln($generatedTestClass->getCode());

        $writePath = $this->createTestClassWriter($configuration)
            ->write($generatedTestClass);

        $output->writeln(\sprintf('Test class written to <info>%s</info>', $writePath));

        return self::SUCCESS;
    }

    private function getClassName(string $className): string
    {
        // path to class was given
        $filePath = \getcwd() . '/' . $className;

        if (\file_exists($filePath)) {
            $beforeClasses = \get_declared_classes();

            require_once $filePath;

            $afterClasses = \get_declared_classes();

            $newClasses = \array_values(\array_diff($afterClasses, $beforeClasses));

            if ( ! isset($newClasses[0])) {
                throw new InvalidArgumentException(\sprintf('Could not find class in file %s', $filePath));
            }

            $className = $newClasses[0];
        }

        return $className;
    }

    private function createConfiguration(): Configuration
    {
        return (new ComposerConfigurationReader())->createConfiguration();
    }

    private function createTestClassGenerator(Configuration $configuration): TestClassGenerator
    {
        return new TestClassGenerator($configuration);
    }

    private function createTestClassWriter(Configuration $configuration): TestClassWriter
    {
        $autoloadingStrategy = $configuration->getAutoloadingStrategy();

        if ($autoloadingStrategy === AutoloadingStrategy::PSR4) {
            return new Psr4TestClassWriter($configuration);
        }

        if ($autoloadingStrategy === AutoloadingStrategy::PSR0) {
            return new Psr0TestClassWriter($configuration);
        }

        throw new InvalidArgumentException(
            \sprintf('Autoloading strategy not supported %s not supported', $autoloadingStrategy),
        );
    }
}
