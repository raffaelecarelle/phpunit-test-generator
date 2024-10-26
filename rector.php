<?php

declare(strict_types=1);

use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;

return RectorConfig::configure()
    ->withImportNames(
        importShortClasses: false
    )
    ->withParallel()
    ->withPaths([
        __FILE__,
        __DIR__ . '/lib',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
    )
    ->withSets([
        PHPUnitSetList::PHPUNIT_110
    ])
    ->withRules(
        [
            ReadOnlyClassRector::class
        ]
    )
    ->withSkip([
        ReturnNeverTypeRector::class,
    ]);
