<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator;

final readonly class TestClassMetadata
{
    /**
     * @param mixed[] $useStatements
     * @param mixed[] $properties
     * @param mixed[] $setUpDependencies
     * @param mixed[] $testMethods
     */
    public function __construct(private array $useStatements, private array $properties, private array $setUpDependencies, private array $testMethods) {}

    /**
     * @return mixed[]
     */
    public function getUseStatements(): array
    {
        return $this->useStatements;
    }

    /**
     * @return mixed[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return mixed[]
     */
    public function getSetUpDependencies(): array
    {
        return $this->setUpDependencies;
    }

    /**
     * @return mixed[]
     */
    public function getTestMethods(): array
    {
        return $this->testMethods;
    }
}
