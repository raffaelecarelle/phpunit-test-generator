<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator\Tests\Fixture;

class TestClass3
{
    private string $name;
    private int $age;

    public function __construct(
        string $name,
        int $age
    )
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function getSomething(): string {}

    public function setSomething(string $someThing): void {}
}
