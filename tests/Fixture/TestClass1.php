<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Tests\Fixture;

use PHPUnitTestGenerator\Tests\TestDependency;

class TestClass1
{
    /**
     * @param mixed[] $testArrayArgument
     */
    public function __construct(private TestDependency $testDependency, private readonly float $testFloatArgument, private readonly int $testIntegerArgument, private readonly string $testStringArgument, private readonly array $testArrayArgument) {}

    public function getTestDependency(): TestDependency
    {
        return $this->testDependency;
    }

    public function setTestDependency(TestDependency $testDependency): void
    {
        $this->testDependency = $testDependency;
    }

    public function getTestFloatArgument(): float
    {
        return $this->testFloatArgument;
    }

    public function getTestIntegerArgument(): int
    {
        return $this->testIntegerArgument;
    }

    public function getTestStringArgument(): string
    {
        return $this->testStringArgument;
    }

    /**
     * @return mixed[]
     */
    public function getTestArrayArgument(): array
    {
        return $this->testArrayArgument;
    }

    public function getTestMethodWithArguments(string $a, float $b, int $c): void {}

    public function getSomething(): string
    {
        return 'something';
    }

    public function getTestBoolean(): bool
    {
        return true;
    }

    /**
     * @return mixed[]
     */
    public function getTestArray(): array
    {
        return [];
    }
}
