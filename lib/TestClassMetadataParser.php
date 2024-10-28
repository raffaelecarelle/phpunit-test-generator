<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator;

use Doctrine\Inflector\Inflector;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

final class TestClassMetadataParser
{
    public const string DEPENDENCY = 'dependency';
    public const string NORMAL = 'normal';
    public const string SUT = 'sut';

    private readonly Inflector $inflector;

    private ?ReflectionClass $reflectionClass = null;

    private ?string $classShortName = null;

    private ?string $classCamelCaseName = null;

    public function __construct(?Inflector $inflector = null)
    {
        $this->inflector = $inflector ?? InflectorFactory::createEnglishInflector();
    }

    public function getTestClassMetadata(string $className): TestClassMetadata
    {
        $this->reflectionClass = new ReflectionClass($className);

        $this->classShortName = $this->reflectionClass->getShortName();
        $this->classCamelCaseName = $this->inflector->camelize($this->classShortName);

        return new TestClassMetadata(
            $this->generateUseStatements(),
            $this->generateClassProperties(),
            $this->generateSetUpLines(),
            $this->generateTestMethods(),
        );
    }

    /**
     * @return mixed[]
     */
    private function generateUseStatements(): array
    {
        $useStatements = [];
        $useStatements[] = $this->reflectionClass->name;
        $useStatements[] = TestCase::class;

        $parameters = $this->getConstructorParameters();

        foreach ($parameters as $parameter) {
            $parameterClass = $this->getParameterClass($parameter);

            if (!$parameterClass instanceof \ReflectionClass) {
                continue;
            }

            $useStatements[] = $parameterClass->getName();
        }

        foreach ($this->reflectionClass->getMethods() as $method) {
            if ( ! $this->isMethodTestable($method)) {
                continue;
            }

            foreach ($method->getParameters() as $parameter) {
                $parameterClass = $this->getParameterClass($parameter);

                if (!$parameterClass instanceof \ReflectionClass) {
                    continue;
                }

                $useStatements[] = $parameterClass->getName();
            }

            $returnType = $method->getReturnType();

            if ($returnType !== null) {
                $returnTypeName = $returnType->getName();

                if ( ! \class_exists($returnTypeName)) {
                    continue;
                }
            }

            $useStatements[] = (string) $returnType;
        }

        $useStatements[] = MockObject::class;

        $useStatements = \array_unique($useStatements);

        \sort($useStatements);

        return $useStatements;
    }

    /**
     * @return mixed[]
     */
    private function generateClassProperties(): array
    {
        $classProperties = [];

        $parameters = $this->getConstructorParameters();

        foreach ($parameters as $parameter) {
            $parameterClass = $this->getParameterClass($parameter);

            if ($parameterClass instanceof \ReflectionClass) {
                $classProperties[] = [
                    'type' => self::DEPENDENCY,
                    'propertyType' => $parameterClass->getShortName(),
                    'propertyName' => $parameter->name,
                ];
            } else {
                $classProperties[] = [
                    'type' => self::NORMAL,
                    'propertyType' => (string) $parameter->getType(),
                    'propertyName' => $parameter->name,
                ];
            }
        }

        $classProperties[] = [
            'type' => self::NORMAL,
            'propertyType' => $this->classShortName,
            'propertyName' => $this->classCamelCaseName,
        ];

        return $classProperties;
    }

    /**
     * @return mixed[]
     */
    private function generateSetUpLines(): array
    {
        $classShortName = $this->reflectionClass->getShortName();
        $classCamelCaseName = $this->inflector->camelize($classShortName);

        $setUpLines = [];

        $parameters = $this->getConstructorParameters();

        foreach ($parameters as $parameter) {
            $parameterClass = $this->getParameterClass($parameter);

            if ($parameterClass instanceof \ReflectionClass) {
                $setUpLines[] = [
                    'type' => self::DEPENDENCY,
                    'propertyName' => $parameter->name,
                    'propertyType' => $parameterClass->getShortName(),
                ];
            } else {
                $typeRandomValue = $this->generateTypeRandomValue((string) $parameter->getType());

                $setUpLines[] = [
                    'type' => self::NORMAL,
                    'propertyName' => $parameter->name,
                    'propertyValue' => $typeRandomValue,
                ];
            }
        }

        $setUpLines[] = [
            'type' => self::SUT,
            'propertyName' => $classCamelCaseName,
            'propertyType' => $classShortName,
            'arguments' => \array_map(static fn(ReflectionParameter $parameter): string => $parameter->name, $parameters),
        ];

        return $setUpLines;
    }

    /**
     * @return ReflectionParameter[]
     */
    private function getConstructorParameters(): array
    {
        $constructor = $this->reflectionClass->getConstructor();

        if ($constructor !== null) {
            return $constructor->getParameters();
        }

        return [];
    }

    /**
     * @return mixed[]
     */
    private function generateTestMethods(): array
    {
        $testMethods = [];

        foreach ($this->reflectionClass->getMethods() as $method) {
            if ( ! $this->isMethodTestable($method)) {
                continue;
            }

            $testMethods[] = [
                'methodName' => \sprintf('test%s', \ucfirst($method->name)),
                'lines' => $this->generateTestMethodLines($method),
            ];
        }

        return $testMethods;
    }

    /**
     * @return mixed[]
     */
    private function generateTestMethodLines(ReflectionMethod $method): array
    {
        $parameters = $method->getParameters();

        $testMethodLines = [];

        foreach ($parameters as $parameter) {
            $parameterClass = $this->getParameterClass($parameter);

            if ($parameterClass instanceof \ReflectionClass) {
                $testMethodLines[] = [
                    'type' => self::DEPENDENCY,
                    'variableName' => $parameter->name,
                    'variableType' => $parameterClass->getShortName(),
                ];
            } else {
                $testMethodLines[] = [
                    'type' => self::NORMAL,
                    'variableName' => $parameter->name,
                ];
            }
        }

        $returnType = $method->getReturnType();
        $returnTypeName = '';

        if ($returnType !== null) {
            $returnTypeName = $returnType->getName();
        }

        $testMethodLines[] = [
            'type' => self::SUT,
            'variableName' => $this->classCamelCaseName,
            'methodName' => $method->name,
            'methodReturnType' => $returnTypeName,
            'arguments' => \array_map(static fn(ReflectionParameter $parameter): string => $parameter->name, $parameters),
        ];

        return $testMethodLines;
    }

    private function isMethodTestable(ReflectionMethod $method): bool
    {
        if ($this->reflectionClass->name !== $method->class) {
            return false;
        }

        return ! str_starts_with($method->name, '__') && $method->isPublic();
    }

    private function generateTypeRandomValue(string $type): array | float | int | string | true
    {
        return match ($type) {
            'array' => [],
            'bool' => true,
            'float' => 1.0,
            'int' => 1,
            'string' => '',
            default => '',
        };
    }

    /**
     * @throws ReflectionException
     */
    private function getParameterClass(ReflectionParameter $parameter): ?ReflectionClass
    {
        return $parameter->getType() && ! $parameter->getType()->isBuiltin()
            ? new ReflectionClass($parameter->getType()->getName())
            : null;
    }
}
