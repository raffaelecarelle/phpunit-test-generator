<?php

declare(strict_types=1);

namespace JWage\PHPUnitTestGenerator\Tests\Writer;

use JWage\PHPUnitTestGenerator\Configuration\AutoloadingStrategy;
use JWage\PHPUnitTestGenerator\Configuration\Configuration;
use JWage\PHPUnitTestGenerator\Configuration\ConfigurationBuilder;
use JWage\PHPUnitTestGenerator\GeneratedTestClass;
use JWage\PHPUnitTestGenerator\Writer\Psr4TestClassWriter;
use Override;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

class Psr4TestClassWriterTest extends TestCase
{
    private Configuration $configuration;

    /**
     * @var Filesystem|MockObject
     */
    private MockObject $filesystem;

    private Psr4TestClassWriter $psr4TestClassWriter;

    public function testWrite(): void
    {
        $generatedTestClass = new GeneratedTestClass(
            'App\User',
            'App\Tests\UserTest',
            '<?php echo "Hello World";',
        );

        $matcher = self::exactly(2);
        $this->filesystem->expects($matcher)
            ->method('exists')
            ->willReturnCallback(function (...$parameters) use ($matcher): void {
                if ($matcher->numberOfInvocations() === 1) {
                    self::assertEquals(['/data/tests'], $parameters);
                }

                if ($matcher->numberOfInvocations() === 2) {
                    self::assertEquals(['/data/tests/UserTest.php'], $parameters);
                }
            })->willReturnOnConsecutiveCalls(false, false)
        ;

        $this->filesystem->expects(self::once())
            ->method('mkdir')
            ->with('/data/tests');

        $this->filesystem->expects(self::once())
            ->method('dumpFile')
            ->with('/data/tests/UserTest.php', '<?php echo "Hello World";');

        $writePath = $this->psr4TestClassWriter->write($generatedTestClass);

        self::assertSame('/data/tests/UserTest.php', $writePath);
    }

    public function testWriteTestClassAlreadyExists(): void
    {
        $generatedTestClass = new GeneratedTestClass(
            'App\User',
            'App\Tests\UserTest',
            '<?php echo "Hello World";',
        );

        $matcher = self::exactly(2);
        $this->filesystem->expects($matcher)
            ->method('exists')
            ->willReturnCallback(function (...$parameters) use ($matcher): void {
                if ($matcher->numberOfInvocations() === 1) {
                    self::assertEquals(['/data/tests'], $parameters);
                }

                if ($matcher->numberOfInvocations() === 2) {
                    self::assertEquals(['/data/tests/UserTest.php'], $parameters);
                }
            })->willReturnOnConsecutiveCalls(false, true)
        ;

        $this->filesystem->expects(self::once())
            ->method('mkdir')
            ->with('/data/tests');

        $this->filesystem->expects(self::never())
            ->method('dumpFile');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Test class already exists at /data/tests/UserTest.php');

        $this->psr4TestClassWriter->write($generatedTestClass);
    }

    #[Override]
    protected function setUp(): void
    {
        $this->configuration = (new ConfigurationBuilder())
            ->setAutoloadingStrategy(AutoloadingStrategy::PSR4)
            ->setSourceNamespace('App')
            ->setSourceDir('/data/lib')
            ->setTestsNamespace('App\Tests')
            ->setTestsDir('/data/tests')
            ->build();

        $this->filesystem = $this->createMock(Filesystem::class);

        $this->psr4TestClassWriter = new Psr4TestClassWriter(
            $this->configuration,
            $this->filesystem,
        );
    }
}
