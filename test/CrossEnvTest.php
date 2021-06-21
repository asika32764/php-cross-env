<?php
/**
 * Part of cross-env project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    MIT
 */

namespace CrossEnv\Test;

use CrossEnv\CrossEnv;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Windwalker\Test\Traits\BaseAssertionTrait;

/**
 * The CrossEnvTest class.
 *
 * @since  {DEPLOY_VERSION}
 */
class CrossEnvTest extends TestCase
{
    use BaseAssertionTrait;

    /**
     * Test instance.
     *
     * @var object
     */
    protected $instance;

    public function testRunWithoutEnv()
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

    public function testRunWithEnv()
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

    public function testRunWithCallback()
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
            static function (string $type, string $buffer) use (&$output) {
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

    public function testRunWithCommand()
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

    public function testRunWithArgs()
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

    public function testRunNotFound()
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

    public function testIsWindows()
    {
        self::assertEquals(CrossEnv::isWindows(), DIRECTORY_SEPARATOR === '\\');
    }

    public function testSignals()
    {
        if (!function_exists('pcntl_signal')) {
            self::markTestSkipped('pcntl_signal() not supported.');
        }

        $handler = static function () {
        };

        if (StubCrossEnv::isWindows()) {
            self::expectException(RuntimeException::class);
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
