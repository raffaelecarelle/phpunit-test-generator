<?php

declare(strict_types=1);

namespace PHPUnitTestGenerator;

use Doctrine\Inflector\CachedWordInflector;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\Rules\English\Rules;
use Doctrine\Inflector\RulesetInflector;

final class InflectorFactory
{
    public static function createEnglishInflector(): Inflector
    {
        return new Inflector(
            new CachedWordInflector(new RulesetInflector(
                Rules::getSingularRuleset(),
            )),
            new CachedWordInflector(new RulesetInflector(
                Rules::getPluralRuleset(),
            )),
        );
    }
}
