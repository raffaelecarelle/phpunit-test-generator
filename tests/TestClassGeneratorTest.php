<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PHPUnitTestGenerator\Configuration\ConfigurationBuilder;
use PHPUnitTestGenerator\TestClassGenerator;
use PHPUnitTestGenerator\Tests\Fixture\TestClass1;
use PHPUnitTestGenerator\Tests\Fixture\TestClass2;

class TestClassGeneratorTest extends TestCase
{
    private const string EXPECTED_TEST_CLASS1 = <<<'EOF'
        <?php

        declare (strict_types=1);

        namespace PHPUnitTestGenerator\Tests\Fixture;

        use PHPUnitTestGenerator\Tests\Fixture\TestClass1;
        use PHPUnitTestGenerator\Tests\TestDependency;
        use PHPUnit\Framework\MockObject\MockObject;
        use PHPUnit\Framework\TestCase;

        class TestClass1Test extends TestCase
        {
            /** @var TestDependency&MockObject */
            private TestDependency $testDependency;

            private float $testFloatArgument;

            private int $testIntegerArgument;

            private string $testStringArgument;

            private array $testArrayArgument;

            private TestClass1 $testClass1;

            public function testGetTestDependency(): void
            {
                self::assertInstanceOf(TestDependency::class, $this->testClass1->getTestDependency());
            }

            public function testSetTestDependency(): void
            {
                $testDependency = $this->createMock(TestDependency::class);
                self::assertNull($this->testClass1->setTestDependency($testDependency));
            }

            public function testGetTestFloatArgument(): void
            {
                self::assertSame(1.0, $this->testClass1->getTestFloatArgument());
            }

            public function testGetTestIntegerArgument(): void
            {
                self::assertSame(1, $this->testClass1->getTestIntegerArgument());
            }

            public function testGetTestStringArgument(): void
            {
                self::assertSame('', $this->testClass1->getTestStringArgument());
            }

            public function testGetTestArrayArgument(): void
            {
                self::assertSame([], $this->testClass1->getTestArrayArgument());
            }

            public function testGetTestMethodWithArguments(): void
            {
                $a = '';
                $b = '';
                $c = '';
                self::assertNull($this->testClass1->getTestMethodWithArguments($a, $b, $c));
            }

            public function testGetSomething(): void
            {
                self::assertSame('', $this->testClass1->getSomething());
            }

            public function testGetTestBoolean(): void
            {
                self::assertTrue($this->testClass1->getTestBoolean());
            }

            public function testGetTestArray(): void
            {
                self::assertSame([], $this->testClass1->getTestArray());
            }

            protected function setUp(): void
            {
                $this->testDependency = $this->createMock(TestDependency::class);
                $this->testFloatArgument = 1.0;
                $this->testIntegerArgument = 1;
                $this->testStringArgument = '';
                $this->testArrayArgument = [];
                $this->testClass1 = new TestClass1($this->testDependency, $this->testFloatArgument, $this->testIntegerArgument, $this->testStringArgument, $this->testArrayArgument);
            }
        }

        EOF;

    private const string EXPECTED_TEST_CLASS2 = <<<'EOF'
        <?php

        declare (strict_types=1);

        namespace PHPUnitTestGenerator\Tests\Fixture;

        use PHPUnitTestGenerator\Tests\Fixture\TestClass2;
        use PHPUnit\Framework\MockObject\MockObject;
        use PHPUnit\Framework\TestCase;

        class TestClass2Test extends TestCase
        {
            private TestClass2 $testClass2;

            public function testGetSomething(): void
            {
                self::assertSame('', $this->testClass2->getSomething());
            }

            protected function setUp(): void
            {
                $this->testClass2 = new TestClass2();
            }
        }

        EOF;

    /**
     * @param class-string $class
     */
    #[DataProvider('getTestClasses')]
    public function testGenerate(string $class, string $expected): void
    {
        $configuration = (new ConfigurationBuilder())->build();

        $testClassGenerator = new TestClassGenerator($configuration);

        $generatedTestClass = $testClassGenerator->generate($class);

        self::assertSame($expected, $generatedTestClass->getCode());
    }

    /**
     * @return string[][]
     */
    public static function getTestClasses(): array
    {
        return [
            [TestClass1::class, self::EXPECTED_TEST_CLASS1],
            [TestClass2::class, self::EXPECTED_TEST_CLASS2],
        ];
    }
}
