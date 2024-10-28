<?php

declare (strict_types=1);

namespace JWage\PHPUnitTestGenerator\Tests;

use JWage\PHPUnitTestGenerator\Tests\Fixture\TestClass1;
use Doctrine\Inflector\Inflector;
use JWage\PHPUnitTestGenerator\TestClassMetadata;
use JWage\PHPUnitTestGenerator\TestClassMetadataParser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TestClassMetadataParserTest extends TestCase
{
    /** @var Inflector|MockObject */
    private Inflector $inflector;

    /** @var TestClassMetadataParser */
    private TestClassMetadataParser $testClassMetadataParser;

    public function testGetTestClassMetadata(): void
    {
        $className = TestClass1::class;
        self::assertInstanceOf(TestClassMetadata::class, $this->testClassMetadataParser->getTestClassMetadata($className));

    }

    protected function setUp(): void
    {
        $this->inflector = $this->createMock(Inflector::class);
        $this->testClassMetadataParser = new TestClassMetadataParser($this->inflector);
    }
}
