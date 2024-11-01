<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Tests\Fixture;

use PHPUnit\Framework\TestCase;

class TestClass3Test extends TestCase
{
    private TestClass3 $testClass3;

    private string $name;

    public function testGetSomething(): void
    {
        self::assertSame('', $this->testClass3->getSomething());
    }

    #[\Override]
    protected function setUp(): void
    {
        $this->name = 'Bob';

        $this->generatedTestClass = new TestClass3($this->name);
    }
}
