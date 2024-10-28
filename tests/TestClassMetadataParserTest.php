<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator\Tests;

use Doctrine\Inflector\Inflector;
use JWage\PHPUnitTestGenerator\TestClassMetadata;
use JWage\PHPUnitTestGenerator\TestClassMetadataParser;
use JWage\PHPUnitTestGenerator\Tests\Fixture\TestClass1;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TestClassMetadataParserTest extends TestCase
{
    private Inflector $inflector;

    private TestClassMetadataParser $testClassMetadataParser;

    public function testGetTestClassMetadata(): void
    {
        $className = TestClass1::class;
        self::assertInstanceOf(TestClassMetadata::class, $this->testClassMetadataParser->getTestClassMetadata($className));
    }

    #[\Override]
    protected function setUp(): void
    {
        $this->inflector = $this->createMock(Inflector::class);
        $this->testClassMetadataParser = new TestClassMetadataParser($this->inflector);
    }
}
