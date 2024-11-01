<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Tests;

use Override;
use PHPUnit\Framework\TestCase;
use PHPUnitTestGenerator\TestClassMetadata;

class TestClassMetadataTest extends TestCase
{
    /**
     * @var mixed[]
     */
    private array $useStatements;

    /**
     * @var mixed[]
     */
    private array $properties;

    /**
     * @var mixed[]
     */
    private array $setUpDependencies;

    /**
     * @var mixed[]
     */
    private array $testMethods;

    private TestClassMetadata $testClassMetadata;

    public function testGetUseStatements(): void
    {
        self::assertSame($this->useStatements, $this->testClassMetadata->getUseStatements());
    }

    public function testGetProperties(): void
    {
        self::assertSame($this->properties, $this->testClassMetadata->getProperties());
    }

    public function testGetSetUpDependencies(): void
    {
        self::assertSame($this->setUpDependencies, $this->testClassMetadata->getSetUpDependencies());
    }

    public function testGetTestMethods(): void
    {
        self::assertSame($this->testMethods, $this->testClassMetadata->getTestMethods());
    }

    #[Override]
    protected function setUp(): void
    {
        $this->useStatements = [self::class];
        $this->properties = ['property'];
        $this->setUpDependencies = ['setUpDependency'];
        $this->testMethods = ['testMethod'];

        $this->testClassMetadata = new TestClassMetadata(
            $this->useStatements,
            $this->properties,
            $this->setUpDependencies,
            $this->testMethods,
        );
    }
}
