<?php

declare(strict_types=1);

namespace CrossEnv\Test;

use CrossEnv\CrossEnv;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Windwalker\Test\Traits\BaseAssertionTrait;

class CrossEnvTest extends TestCase
{
    use BaseAssertionTrait;

    protected CrossEnv $instance;

    public function testRunWithoutEnv(): void
    {
        $code = (new StubCrossEnv())->run(
            [
                'cross-env',
                'php',
                __DIR__ . '/test.php',
            ]
        );

        $result = <<<TEXT
HELLO
TEXT;

        self::assertStringSafeEquals($result, StubCrossEnv::$output);
        self::assertEquals(0, $code);
    }

    public function testRunWithEnv(): void
    {
        $code = (new StubCrossEnv())->run(
            [
                'cross-env',
                'TEST_APP_ENV=dev',
                'TEST_NODE_ENV="production"',
                'TEST_CLI_ENV=\'test\'',
                'php',
                __DIR__ . '/test.php',
            ]
        );

        $result = <<<TEXT
HELLO

TEST_APP_ENV=dev
TEST_CLI_ENV=test
TEST_NODE_ENV=production
TEXT;

        self::assertStringSafeEquals($result, StubCrossEnv::$output);
        self::assertEquals(0, $code);
    }

    public function testRunWithEnvAndParameters(): void
    {
        $code = (new StubCrossEnv())->run(
            [
                'cross-env',
                'TEST_APP_ENV=dev',
                'TEST_NODE_ENV="production"',
                'TEST_CLI_ENV=\'test\'',
                'php',
                __DIR__ . '/test.php',
                'main:go',
                '--foo',
                '--bar=baz',
                '-f'
            ]
        );

        $result = <<<TEXT
HELLO

TEST_APP_ENV=dev
TEST_CLI_ENV=test
TEST_NODE_ENV=production
main:go
--foo
--bar=baz
-f
TEXT;

        self::assertStringSafeEquals($result, StubCrossEnv::$output);
        self::assertEquals(0, $code);
    }

    public function testRunWithCallback(): void
    {
        $output = '';

        $code = (new StubCrossEnv())->run(
            [
                'cross-env',
                'TEST_APP_ENV=dev',
                'TEST_NODE_ENV="production"',
                'TEST_CLI_ENV=\'test\'',
                'php',
                __DIR__ . '/test.php',
            ],
            static function (string $type, string $buffer) use (&$output): void {
                $output .= $buffer;
            }
        );

        $result = <<<TEXT
HELLO

TEST_APP_ENV=dev
TEST_CLI_ENV=test
TEST_NODE_ENV=production
TEXT;

        self::assertStringSafeEquals('', StubCrossEnv::$output);
        self::assertStringSafeEquals($result, $output);
        self::assertEquals(0, $code);
    }

    public function testRunWithCommand(): void
    {
        $code = StubCrossEnv::runWithCommand(
            sprintf(
                'TEST_APP_ENV=dev TEST_NODE_ENV="production" TEST_CLI_ENV=\'test\' php %s',
                __DIR__ . '/test.php'
            )
        );

        $result = <<<TEXT
HELLO

TEST_APP_ENV=dev
TEST_CLI_ENV=test
TEST_NODE_ENV=production
TEXT;

        self::assertStringSafeEquals($result, StubCrossEnv::$output);
        self::assertEquals(0, $code);
    }

    public function testRunWithArgs(): void
    {
        $code = StubCrossEnv::runWithArgs(
            [
                'TEST_APP_ENV=dev',
                'TEST_NODE_ENV="production"',
                'TEST_CLI_ENV=\'test\'',
                'php',
                __DIR__ . '/test.php',
            ]
        );

        $result = <<<TEXT
HELLO

TEST_APP_ENV=dev
TEST_CLI_ENV=test
TEST_NODE_ENV=production
TEXT;

        self::assertStringSafeEquals($result, StubCrossEnv::$output);
        self::assertEquals(0, $code);
    }

    public function testRunNotFound(): void
    {
        $code = (new StubCrossEnv())->run(
            [
                'cross-env',
                'php',
                __DIR__ . '/foo.php',
            ]
        );

        self::assertEquals(1, $code);
    }

    public function testIsWindows(): void
    {
        self::assertEquals(CrossEnv::isWindows(), DIRECTORY_SEPARATOR === '\\');
    }

    public function testSignals(): void
    {
        if (!function_exists('pcntl_signal')) {
            self::markTestSkipped('pcntl_signal() not supported.');
        }

        $handler = static function (): void {
        };

        if (StubCrossEnv::isWindows()) {
            $this->expectException(RuntimeException::class);
        }

        StubCrossEnv::signals([123, 321], $handler);

        self::assertEquals(StubCrossEnv::$signals[123], $handler);
        self::assertEquals(StubCrossEnv::$signals[321], $handler);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->instance = new CrossEnv();

        StubCrossEnv::reset();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown(): void
    {
    }
}
