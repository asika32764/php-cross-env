<?php
/**
 * Part of Windwalker project Test files.  @codingStandardsIgnoreStart
 *
 * @copyright  Copyright (C) 2019 LYRASOFT Taiwan, Inc.
 * @license    LGPL-2.0-or-later
 */

namespace Windwalker\String\Test;

use Windwalker\String\StringHelper;

/**
 * Test class of String
 *
 * @since 2.0
 */
class StringHelperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Method to test isEmptyString().
     *
     * @return void
     *
     * @covers \Windwalker\String\StringHelper::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertTrue(StringHelper::isEmpty(''));
        $this->assertFalse(StringHelper::isEmpty(0));
        $this->assertFalse(StringHelper::isEmpty(' '));
        $this->assertTrue(StringHelper::isEmpty(null));
        $this->assertFalse(StringHelper::isEmpty(true));
        $this->assertTrue(StringHelper::isEmpty(false));
    }

    /**
     * Method to test isZero().
     *
     * @return void
     *
     * @covers \Windwalker\String\StringHelper::isZero
     */
    public function testIsZero()
    {
        $this->assertTrue(StringHelper::isZero(0));
        $this->assertTrue(StringHelper::isZero('0'));
        $this->assertFalse(StringHelper::isZero(''));
        $this->assertFalse(StringHelper::isZero(null));
        $this->assertFalse(StringHelper::isZero(true));
        $this->assertFalse(StringHelper::isZero(false));
    }

    /**
     * Method to test quote().
     *
     * @return void
     *
     * @covers \Windwalker\String\StringHelper::quote
     */
    public function testQuote()
    {
        $this->assertEquals('"foo"', StringHelper::quote('foo'));
        $this->assertEquals('"foo"', StringHelper::quote('foo', '"'));
        $this->assertEquals('"foo"', StringHelper::quote('foo', ['"', '"']));
        $this->assertEquals('[foo]', StringHelper::quote('foo', ['[', ']']));
    }

    /**
     * Method to test backquote().
     *
     * @return void
     *
     * @covers \Windwalker\String\StringHelper::backquote
     */
    public function testBackquote()
    {
        $this->assertEquals('`foo`', StringHelper::backquote('foo'));
    }

    /**
     * Test...
     *
     * @return  array
     *
     * @since   2.0
     */
    public function seedTestIncrement()
    {
        return [
            // Note: string, style, number, expected
            'First default increment' => ['title', null, 0, 'title (2)'],
            'Second default increment' => ['title(2)', null, 0, 'title(3)'],
            'First dash increment' => ['title', 'dash', 0, 'title-2'],
            'Second dash increment' => ['title-2', 'dash', 0, 'title-3'],
            'Set default increment' => ['title', null, 4, 'title (4)'],
            'Unknown style fallback to default' => ['title', 'foo', 0, 'title (2)'],
        ];
    }

    /**
     * Test...
     *
     * @param   string $string   String to increment.
     * @param   string $style    Default of Dash.
     * @param   string $number   Number to increment.
     * @param   string $expected Expected value.
     *
     * @return  void
     *
     * @covers        \Windwalker\String\StringHelper::increment
     * @dataProvider  seedTestIncrement
     * @since         2.0
     */
    public function testIncrement($string, $style, $number, $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::increment($string, $style, $number)
        );
    }

    /**
     * testAt
     *
     * @return  void
     */
    public function testAt()
    {
        $string = 'Foo Bar';

        $this->assertEquals('F', StringHelper::at($string, 0));
        $this->assertEquals('o', StringHelper::at($string, 1));
        $this->assertNull(StringHelper::at($string, 10));
    }

    /**
     * testEndsWith
     *
     * @return  void
     */
    public function testEndsWith()
    {
        $string = 'Foo';

        $this->assertTrue(StringHelper::endsWith($string, 'oo'));
        $this->assertFalse(StringHelper::endsWith($string, 'Oo'));
        $this->assertTrue(StringHelper::endsWith($string, 'Oo', false));
        $this->assertFalse(StringHelper::endsWith($string, 'ooooo'));
        $this->assertFalse(StringHelper::endsWith($string, 'uv'));
    }

    /**
     * testStartsWith
     *
     * @return  void
     */
    public function testStartsWith()
    {
        $string = 'Foo';

        $this->assertTrue(StringHelper::startsWith($string, 'Fo'));
        $this->assertFalse(StringHelper::startsWith($string, 'fo'));
        $this->assertTrue(StringHelper::startsWith($string, 'fo', false));
        $this->assertFalse(StringHelper::startsWith($string, 'ooooo'));
        $this->assertFalse(StringHelper::startsWith($string, 'uv'));
    }

    /**
     * testCollapseWhitespace
     *
     * @return  void
     */
    public function testCollapseWhitespace()
    {
        $this->assertEquals('foo bar', StringHelper::collapseWhitespace(' foo   bar  '));
        $this->assertEquals('foo bar', StringHelper::collapseWhitespace(" foo \n \r  bar \n "));
    }
}
