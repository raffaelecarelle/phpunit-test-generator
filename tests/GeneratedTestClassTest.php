<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Tests;

use Override;
use PHPUnit\Framework\TestCase;
use PHPUnitTestGenerator\GeneratedTestClass;

class GeneratedTestClassTest extends TestCase
{
    private string $className;

    private string $testClassName;

    private string $code;

    private GeneratedTestClass $generatedTestClass;

    public function testGetClassName(): void
    {
        self::assertSame($this->className, $this->generatedTestClass->getClassName());
    }

    public function testGetTestClassName(): void
    {
        self::assertSame($this->testClassName, $this->generatedTestClass->getTestClassName());
    }

    public function testGetCode(): void
    {
        self::assertSame($this->code, $this->generatedTestClass->getCode());
    }

    #[Override]
    protected function setUp(): void
    {
        $this->className = 'App\User';
        $this->testClassName = 'App\Tests\User';
        $this->code = '<?php echo "Hello World";';

        $this->generatedTestClass = new GeneratedTestClass(
            $this->className,
            $this->testClassName,
            $this->code,
        );
    }
}
