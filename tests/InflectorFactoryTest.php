<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnitTestGenerator\InflectorFactory;

class InflectorFactoryTest extends TestCase
{
    public function testCreateEnglishInflector(): void
    {
        $inflector = InflectorFactory::createEnglishInflector();

        self::assertSame('apple', $inflector->singularize('apples'));
    }
}
