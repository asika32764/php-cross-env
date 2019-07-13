<?php
/**
 * Part of ww4 project.
 *
 * @copyright  Copyright (C) 2019 LYRASOFT.
 * @license    Please see LICENSE file.
 */

namespace Windwalker\String\Test;

use PHPUnit\Framework\TestCase;
use Windwalker\String\Str;

/**
 * The StrTest class.
 *
 * @since  3.2
 */
class StrTest extends TestCase
{
    /**
     * testGetChar
     *
     * @param    $pos
     * @param    $expected
     *
     * @dataProvider getCharProvider
     */
    public function testGetChar($pos, $expected)
    {
        self::assertEquals($expected, Str::getChar('白日依山盡', $pos));
    }

    /**
     * getCharProvider
     *
     * @return  array
     */
    public function getCharProvider()
    {
        return [
            [0, '白'],
            [3, '山'],
            [10, ''],
            [-1, '盡'],
            [-2, '山'],
            [-5, '白'],
            [-16, ''],
        ];
    }

    /**
     * testBetween
     *
     * @param $string
     * @param $expected
     * @param $left
     * @param $right
     *
     * @return  void
     *
     * @dataProvider betweenProvider
     */
    public function testBetween($string, $expected, $left, $right, $offset = 0)
    {
        self::assertEquals($expected, Str::between($string, $left, $right, $offset));
    }

    /**
     * betweenProvider
     *
     * @return  array
     */
    public function betweenProvider()
    {
        return [
            ['fòôbàř', 'ôb', 'ò', 'à'],
            ['To {be} or {not} to be', 'be', '{', '}'],
            ['To {be} or {not} to be', 'not', '{', '}', 4],
            ['To {{be} or {not} to be', '{be', '{', '}'],
            ['{foo} and {bar}', 'bar', '{', '}', 1],
            ['foo and {bar', '', '{', '}', 1],
            ['foo} and bar', '', '{', '}', 1],
        ];
    }

    /**
     * testCollapseWhitespace
     *
     * @return  void
     */
    public function testCollapseWhitespace()
    {
        self::assertEquals('foo bar yoo', Str::collapseWhitespaces('foo  bar    yoo'));
        self::assertEquals('foo bar yoo', Str::collapseWhitespaces('  foo  bar yoo '));
        self::assertEquals('foo bar yoo', Str::collapseWhitespaces("  foo\n \r bar\n\r\n yoo \n"));
    }

    /**
     * testContains
     *
     * @param      $expected
     * @param      $string
     * @param      $search
     * @param      $caseSensitive
     *
     * @dataProvider  containsProvider
     */
    public function testContains($expected, $string, $search, $caseSensitive = true)
    {
        self::assertSame($expected, Str::contains($string, $search, $caseSensitive));
    }

    /**
     * containsProvider
     *
     * @return  array
     */
    public function containsProvider()
    {
        return [
            [true, 'foobar', 'oba'],
            [true, 'fooBar', 'oba', false],
            [false, 'fooBar', 'oba'],
            [true, 'fòôbàř', 'ôbà'],
            [true, '白日依山盡', '日依'],
            [false, '白日依山盡', '梅友仁'],
            [false, 'FÒÔbàř', 'ôbà'],
            [true, 'FÒÔbàř', 'ôbà', false],
        ];
    }

    /**
     * testEndsWith
     *
     * @return  void
     *
     * @dataProvider endsWithProvider
     */
    public function testEndsWith($string, $search, $caseSensitive, $expected)
    {
        self::assertSame($expected, Str::endsWith($string, $search, $caseSensitive));
    }

    /**
     * endsWithProvider
     *
     * @return  array
     */
    public function endsWithProvider()
    {
        return [
            ['Foo', 'oo', Str::CASE_SENSITIVE, true],
            ['Foo', 'Oo', Str::CASE_SENSITIVE, false],
            ['Foo', 'Oo', Str::CASE_INSENSITIVE, true],
            ['Foo', 'ooooo', Str::CASE_SENSITIVE, false],
            ['Foo', 'uv', Str::CASE_SENSITIVE, false],
            ['黃河入海流', '入海流', Str::CASE_SENSITIVE, true],
            ['黃河入海流', '入海流', Str::CASE_INSENSITIVE, true],
            ['黃河入海流', '依山盡', Str::CASE_SENSITIVE, false],
            ['FÒÔbà', 'ôbà', Str::CASE_SENSITIVE, false],
            ['FÒÔbà', 'ôbà', Str::CASE_INSENSITIVE, true],
        ];
    }

    /**
     * testStartsWith
     *
     * @param   $string
     * @param   $search
     * @param   $caseSensitive
     * @param   $expected
     *
     * @dataProvider estartsWithProvider
     */
    public function testStartsWith($string, $search, $caseSensitive, $expected)
    {
        self::assertSame($expected, Str::startsWith($string, $search, $caseSensitive));
    }

    /**
     * endsWithProvider
     *
     * @return  array
     */
    public function estartsWithProvider()
    {
        return [
            ['Foo', 'Fo', Str::CASE_SENSITIVE, true],
            ['Foo', 'fo', Str::CASE_SENSITIVE, false],
            ['Foo', 'fo', Str::CASE_INSENSITIVE, true],
            ['Foo', 'foooo', Str::CASE_SENSITIVE, false],
            ['Foo', 'uv', Str::CASE_SENSITIVE, false],
            ['黃河入海流', '黃河', Str::CASE_SENSITIVE, true],
            ['黃河入海流', '黃河', Str::CASE_INSENSITIVE, true],
            ['黃河入海流', '依山盡', Str::CASE_SENSITIVE, false],
            ['FÒÔbà', 'fò', Str::CASE_SENSITIVE, false],
            ['FÒÔbà', 'fò', Str::CASE_INSENSITIVE, true],
        ];
    }

    /**
     * testEnsureLeft
     *
     * @param $string
     * @param $search
     * @param $expected
     *
     * @dataProvider ensureLeftProvider
     */
    public function testEnsureLeft($string, $search, $expected)
    {
        self::assertSame($expected, Str::ensureLeft($string, $search));
    }

    /**
     * ensureLeftProvider
     *
     * @return  array
     */
    public function ensureLeftProvider()
    {
        return [
            ['FlowerSakura', 'Flower', 'FlowerSakura'],
            ['Sakura', 'Flower', 'FlowerSakura'],
            ['FlowerSakura', 'flower', 'flowerFlowerSakura'],
            ['黃河入海流', '黃河', '黃河入海流'],
            ['入海流', '黃河', '黃河入海流'],
            ['FÒÔbà', 'FÒÔ', 'FÒÔbà'],
            ['FÒÔbà', 'fòô', 'fòôFÒÔbà'],
        ];
    }

    /**
     * testEnsureRight
     *
     * @param $string
     * @param $search
     * @param $expected
     *
     * @return  void
     *
     * @dataProvider ensureRightProvider
     */
    public function testEnsureRight($string, $search, $expected)
    {
        self::assertSame($expected, Str::ensureRight($string, $search));
    }

    /**
     * ensureRightProvider
     *
     * @return  array
     */
    public function ensureRightProvider()
    {
        return [
            ['FlowerSakura', 'Sakura', 'FlowerSakura'],
            ['Flower', 'Sakura', 'FlowerSakura'],
            ['FlowerSakura', 'sakura', 'FlowerSakurasakura'],
            ['黃河入海流', '海流', '黃河入海流'],
            ['黃河入', '海流', '黃河入海流'],
            ['FÒÔbà', 'Ôbà', 'FÒÔbà'],
            ['FÒÔbà', 'ôbà', 'FÒÔbàôbà'],
        ];
    }

    /**
     * testHasLowerCase
     *
     * @param   $string
     * @param   $expected
     *
     * @return  void
     *
     * @dataProvider hasLowerCaseProvider
     */
    public function testHasLowerCase($string, $expected)
    {
        self::assertSame($expected, Str::hasLowerCase($string));
    }

    /**
     * hasLowerCaseProvider
     *
     * @return  array
     */
    public function hasLowerCaseProvider()
    {
        return [
            ['Foo', true],
            ['FOO', false],
            ['FÒô', true],
            ['FÒÔ', false],
            ['白日依山盡', false],
        ];
    }

    /**
     * testHasUpperCase
     *
     * @param   $string
     * @param   $expected
     *
     * @return  void
     *
     * @dataProvider hasUpperCaseProvider
     */
    public function testHasUpperCase($string, $expected)
    {
        self::assertSame($expected, Str::hasUpperCase($string));
    }

    /**
     * hasUpperCaseProvider
     *
     * @return  array
     */
    public function hasUpperCaseProvider()
    {
        return [
            ['Foo', true],
            ['foo', false],
            ['FÒô', true],
            ['fòô', false],
            ['白日依山盡', false],
        ];
    }

    /**
     * testInsert
     *
     * @param    $string
     * @param    $insert
     * @param    $position
     * @param    $expected
     *
     * @return  void
     *
     * @dataProvider insertProvider
     */
    public function testInsert($string, $insert, $position, $expected)
    {
        self::assertEquals($expected, Str::insert($string, $insert, $position));
    }

    /**
     * insertProvider
     *
     * @return  array
     */
    public function insertProvider()
    {
        return [
            ['FlowerSakura', 'And', 6, 'FlowerAndSakura'],
            ['fòàř', 'ôb', 2, 'fòôbàř'],
            ['白日山盡', '依', 2, '白日依山盡'],
            ['白日山盡', '依', 6, '白日山盡'],
        ];
    }

    /**
     * testIsLowerCase
     *
     * @param   $string
     * @param   $expected
     *
     * @return  void
     *
     * @dataProvider  isLowerCaseProvider
     */
    public function testIsLowerCase($string, $expected)
    {
        self::assertSame($expected, Str::isLowerCase($string));
    }

    /**
     * isLowerCase
     *
     * @return  array
     */
    public function isLowerCaseProvider()
    {
        return [
            ['flower', true],
            ['Flower', false],
            ['fòôbàř', true],
            ['fòÔbàř', false],
            ['白日依山盡', false],
        ];
    }

    /**
     * testIsUpperCase
     *
     * @param   $string
     * @param   $expected
     *
     * @dataProvider isUpperCaseProvider
     */
    public function testIsUpperCase($string, $expected)
    {
        self::assertSame($expected, Str::isUpperCase($string));
    }

    /**
     * isUpperCaseProvider
     *
     * @return  array
     */
    public function isUpperCaseProvider()
    {
        return [
            ['FLOWER', true],
            ['Flower', false],
            ['FÒÔBÀŘ', true],
            ['fòÔbàř', false],
            ['白日依山盡', false],
        ];
    }

    /**
     * testFirst
     *
     * @param    $string
     * @param    $length
     * @param    $expected
     *
     * @return  void
     *
     * @dataProvider firstProvider
     */
    public function testFirst($string, $length, $expected)
    {
        self::assertEquals($expected, Str::first($string, $length));
    }

    /**
     * firstProvider
     *
     * @return  array
     */
    public function firstProvider()
    {
        return [
            ['Foobar', 1, 'F'],
            ['Foobar', 3, 'Foo'],
            ['fòôbàř', 1, 'f'],
            ['fòôbàř', 3, 'fòô'],
            ['白日依山盡', 1, '白'],
            ['白日依山盡', 3, '白日依'],
            ['白日依山盡', 8, '白日依山盡'],
            ['白日依山盡', 0, ''],
            ['白日依山盡', -3, ''],
            ['', 5, ''],
        ];
    }

    /**
     * testLast
     *
     * @param    $string
     * @param    $length
     * @param    $expected
     *
     * @return  void
     *
     * @dataProvider lastProvider
     */
    public function testLast($string, $length, $expected)
    {
        self::assertEquals($expected, Str::last($string, $length));
    }

    /**
     * lastProvider
     *
     * @return  array
     */
    public function lastProvider()
    {
        return [
            ['Foobar', 1, 'r'],
            ['Foobar', 3, 'bar'],
            ['fòôbàř', 1, 'ř'],
            ['fòôbàř', 3, 'bàř'],
            ['白日依山盡', 1, '盡'],
            ['白日依山盡', 3, '依山盡'],
            ['白日依山盡', 8, '白日依山盡'],
            ['白日依山盡', 0, ''],
            ['白日依山盡', -8, ''],
            ['', 5, ''],
        ];
    }

    /**
     * testIntersectLeft
     *
     * @return  void
     *
     * @dataProvider intersectLeftProvider
     */
    public function testIntersectLeft($string1, $string2, $expected)
    {
        self::assertEquals($expected, Str::intersectLeft($string1, $string2));
    }

    /**
     * intersectLeftProvider
     *
     * @return  array
     */
    public function intersectLeftProvider()
    {
        return [
            ['foobar', 'foo bar', 'foo'],
            ['foobar', 'frank', 'f'],
            ['foobar', 'sakura', ''],
            ['foobar', '', ''],
            ['fòôbàř', 'fòô bàř', 'fòô'],
            ['fòôbàř', 'fòôbàř', 'fòôbàř'],
            ['fòôbàř', 'fbàř òô', 'f'],
            ['白日依山盡', '白日夢', '白日'],
            ['白日依山盡', '', ''],
        ];
    }

    /**
     * testIntersectRight
     *
     * @param $string1
     * @param $string2
     * @param $expected
     *
     * @return  void
     *
     * @dataProvider intersectRightProvider
     */
    public function testIntersectRight($string1, $string2, $expected)
    {
        self::assertEquals($expected, Str::intersectRight($string1, $string2));
    }

    /**
     * intersectRightProvider
     *
     * @return  array
     */
    public function intersectRightProvider()
    {
        return [
            ['foobar', 'foo bar', 'bar'],
            ['foobar', 'lunar', 'ar'],
            ['foobar', 'sakura', ''],
            ['foobar', '', ''],
            ['fòôbàř', 'fòô bàř', 'bàř'],
            ['fòôbàř', 'fòôbàř', 'fòôbàř'],
            ['fòôbàř', 'fbàòôř', 'ř'],
            ['白日依山盡', '無盡', '盡'],
            ['白日依山盡', '', ''],
        ];
    }

    /**
     * testIntersect
     *
     * @param $string1
     * @param $string2
     * @param $expected
     *
     * @return  void
     *
     * @dataProvider intersectProvider
     */
    public function testIntersect($string1, $string2, $expected)
    {
        self::assertEquals($expected, Str::intersect($string1, $string2));
    }

    /**
     * intersectProvider
     *
     * @return  array
     */
    public function intersectProvider()
    {
        return [
            ['foobar', 'f oob ar', 'oob'],
            ['foobar', 'neobike', 'ob'],
            ['foobar', 'uncle', ''],
            ['foobar', '', ''],
            ['fòôbàř', 'f òôb àř', 'òôb'],
            ['fòôbàř', 'fòôbàř', 'fòôbàř'],
            ['fòôbàř', 'fbàòôř', 'òô'],
            ['漢字順序不一定影響閱讀', '漢字序順不一定影閱響讀', '不一定影'],
            ['白日依山盡', '', ''],
        ];
    }

    /**
     * testPad
     *
     * @param    $string
     * @param    $substring
     * @param    $length
     * @param    $expected
     *
     * @dataProvider padProvider
     */
    public function testPad($string, $substring, $length, $expected)
    {
        self::assertEquals($expected, Str::pad($string, $length, $substring));
    }

    /**
     * padProvider
     *
     * @return  array
     */
    public function padProvider()
    {
        return [
            ['foobar', '_', -1, 'foobar'],
            ['foobar', '_', 3, 'foobar'],
            ['foobar', '_/', 9, '_foobar_/'],
            ['foobar', '_/', 10, '_/foobar_/'],
            ['fòôbàř', '¬ø', 9, '¬fòôbàř¬ø'],
            ['fòôbàř', '¬øÿ', 11, '¬øfòôbàř¬øÿ'],
            ['妳好', '嗨', 5, '嗨妳好嗨嗨'],
        ];
    }

    /**
     * testPadLeft
     *
     * @param    $string
     * @param    $substring
     * @param    $length
     * @param    $expected
     *
     * @return  void
     *
     * @dataProvider padLeftProvider
     */
    public function testPadLeft($string, $substring, $length, $expected)
    {
        self::assertEquals($expected, Str::padLeft($string, $length, $substring));
    }

    /**
     * padLeftProvider
     *
     * @return  array
     */
    public function padLeftProvider()
    {
        return [
            ['foobar', '_', -1, 'foobar'],
            ['foobar', '_', 3, 'foobar'],
            ['foobar', '_/', 9, '_/_foobar'],
            ['foobar', '_/', 10, '_/_/foobar'],
            ['fòôbàř', '¬ø', 9, '¬ø¬fòôbàř'],
            ['fòôbàř', '¬øÿ', 11, '¬øÿ¬øfòôbàř'],
            ['妳好', '嗨', 5, '嗨嗨嗨妳好'],
        ];
    }

    /**
     * testPadRight
     *
     * @param    $string
     * @param    $substring
     * @param    $length
     * @param    $expected
     *
     * @return  void
     *
     * @dataProvider padRightProvider
     */
    public function testPadRight($string, $substring, $length, $expected)
    {
        self::assertEquals($expected, Str::padRight($string, $length, $substring));
    }

    /**
     * padRightProvider
     *
     * @return  array
     */
    public function padRightProvider()
    {
        return [
            ['foobar', '_', -1, 'foobar'],
            ['foobar', '_', 3, 'foobar'],
            ['foobar', '_/', 9, 'foobar_/_'],
            ['foobar', '_/', 10, 'foobar_/_/'],
            ['fòôbàř', '¬ø', 9, 'fòôbàř¬ø¬'],
            ['fòôbàř', '¬øÿ', 11, 'fòôbàř¬øÿ¬ø'],
            ['妳好', '嗨', 5, '妳好嗨嗨嗨'],
        ];
    }

    /**
     * testRemove
     *
     * @param    $string
     * @param    $offset
     * @param    $expected
     *
     * @return  void
     *
     * @dataProvider removeProvider
     */
    public function testRemoveChar($string, $offset, $expected)
    {
        self::assertEquals($expected, Str::removeChar($string, $offset));
    }

    /**
     * removeProvider
     *
     * @return  array
     */
    public function removeProvider()
    {
        return [
            ['Foobar', 3, 'Fooar'],
            ['桃之夭夭', 0, '之夭夭'],
            ['下筆千言', 2, '下筆言'],
            ['白日依山盡', -2, '白日依盡'],
            ['白日依山盡', -5, '日依山盡'],
            ['白日依山盡', 5, '白日依山盡'],
            ['下筆千言', 6, '下筆千言'],
            ['下筆千言', -8, '下筆千言'],
        ];
    }

    /**
     * testRemoveLeft
     *
     * @param $string
     * @param $search
     * @param $expected
     *
     * @return  void
     *
     * @dataProvider removeLeftProvider
     */
    public function testRemoveLeft($string, $search, $expected)
    {
        self::assertEquals($expected, Str::removeLeft($string, $search));
    }

    /**
     * removeRightProvider
     *
     * @return  array
     */
    public function removeLeftProvider()
    {
        return [
            ['foobar', 'hoo', 'foobar'],
            ['foobar', 'fo', 'obar'],
            ['fòôbàř', 'òôb', 'fòôbàř'],
            ['fòôbàř', 'fò', 'ôbàř'],
            ['白日依山盡', '入海流', '白日依山盡'],
            ['白日依山盡', '白日', '依山盡'],
            ['白日依山盡', '', '白日依山盡'],
            ['', '白日', ''],
        ];
    }

    /**
     * testRemoveRight
     *
     * @return  void
     *
     * @dataProvider removeRightProvider
     */
    public function testRemoveRight($string, $search, $expected)
    {
        self::assertEquals($expected, Str::removeRight($string, $search));
    }

    /**
     * removeRightProvider
     *
     * @return  array
     */
    public function removeRightProvider()
    {
        return [
            ['foobar', 'hoo', 'foobar'],
            ['foobar', 'ar', 'foob'],
            ['fòôbàř', 'òôb', 'fòôbàř'],
            ['fòôbàř', 'àř', 'fòôb'],
            ['白日依山盡', '入海流', '白日依山盡'],
            ['白日依山盡', '依山盡', '白日'],
            ['白日依山盡', '', '白日依山盡'],
            ['', '白日', ''],
        ];
    }

    /**
     * testSlice
     *
     * @param    $string
     * @param    $start
     * @param    $end
     * @param    $expected
     *
     * @dataProvider sliceProvider
     */
    public function testSlice($string, $start, $end = null, $expected = '')
    {
        self::assertEquals($expected, Str::slice($string, $start, $end));
    }

    /**
     * sliceProvider
     *
     * @return  array
     */
    public function sliceProvider()
    {
        return [
            ['Foobar Allstar', 0, 3, 'Foo'],
            ['Foobar Allstar', 3, 10, 'bar All'],
            ['Foobar Allstar', 3, null, 'bar Allstar'],
            ['Foobar Allstar', 3, -10, 'b'],
            ['Foobar Allstar', 10, 3, ''],
            ['fòôbàř àřfòôbf', 0, 3, 'fòô'],
            ['fòôbàř àřfòôbf', 3, 10, 'bàř àřf'],
            ['fòôbàř àřfòôbf', 3, null, 'bàř àřfòôbf'],
            ['fòôbàř àřfòôbf', 3, -10, 'b'],
            ['fòôbàř àřfòôbf', 10, 3, ''],
            ['白日依山盡 黃河入海流', 0, 3, '白日依'],
            ['白日依山盡 黃河入海流', 3, 10, '山盡 黃河入海'],
            ['白日依山盡 黃河入海流', 3, 15, '山盡 黃河入海流'],
        ];
    }

    /**
     * testSubstring
     *
     * @param    $string
     * @param    $start
     * @param    $end
     * @param    $expected
     *
     * @return  void
     *
     * @dataProvider substringProvider
     */
    public function testSubstring($string, $start, $end = null, $expected = '')
    {
        self::assertEquals($expected, Str::substring($string, $start, $end));
    }

    /**
     * substringProvider
     *
     * @return  array
     */
    public function substringProvider()
    {
        return [
            ['Foobar Allstar', 0, 3, 'Foo'],
            ['Foobar Allstar', 3, 10, 'bar All'],
            ['Foobar Allstar', 3, null, 'bar Allstar'],
            ['Foobar Allstar', 3, -10, 'b'],
            ['Foobar Allstar', 10, 3, 'bar All'],
            ['fòôbàř àřfòôbf', 0, 3, 'fòô'],
            ['fòôbàř àřfòôbf', 3, 10, 'bàř àřf'],
            ['fòôbàř àřfòôbf', 3, null, 'bàř àřfòôbf'],
            ['fòôbàř àřfòôbf', 3, -10, 'b'],
            ['fòôbàř àřfòôbf', 10, 3, 'bàř àřf'],
            ['白日依山盡 黃河入海流', 0, 3, '白日依'],
            ['白日依山盡 黃河入海流', 3, 10, '山盡 黃河入海'],
            ['白日依山盡 黃河入海流', 3, 15, '山盡 黃河入海流'],
        ];
    }

    /**
     * testSurround
     *
     * @param              $string
     * @param              $expected
     * @param string|array $substring
     *
     * @return  void
     *
     * @dataProvider surroundProvider
     */
    public function testSurround($string, $expected, $sub = null)
    {
        if ($sub === null) {
            self::assertEquals($expected, Str::surround($string));
        } else {
            self::assertEquals($expected, Str::surround($string, $sub));
        }
    }

    /**
     * surroundProvider
     *
     * @return  array
     */
    public function surroundProvider()
    {
        return [
            ['foo', '"foo"'],
            ['foo', '"foo"', '"'],
            ['foo', '"foo"', ['"', '"']],
            ['foo', '[foo]', ['[', ']']],
        ];
    }

    /**
     * testToggleCase
     *
     * @param $string
     * @param $expected
     *
     * @return  void
     *
     * @dataProvider toggleCaseProvider
     */
    public function testToggleCase($string, $expected)
    {
        self::assertEquals($expected, Str::toggleCase($string));
    }

    /**
     * toggleCaseProvider
     *
     * @return  array
     */
    public function toggleCaseProvider()
    {
        return [
            ['FooBar', 'fOObAR'],
            ['foobar', 'FOOBAR'],
            ['FòôBàř', 'fÒÔbÀŘ'],
            ['fòôbàř', 'FÒÔBÀŘ'],
            ['白日依山盡', '白日依山盡'],
        ];
    }

    /**
     * testTruncate
     *
     * @param    $string
     * @param    $length
     * @param    $wordBreak
     * @param    $expected
     * @param    $suffix
     *
     * @dataProvider truncateProvider
     */
    public function testTruncate(
        $string,
        $length,
        $expected = '',
        $suffix = '',
        $wordBreak = true
    ) {
        self::assertEquals($expected, Str::truncate($string, $length, $suffix, $wordBreak));
    }

    /**
     * truncateProvider
     *
     * @return  array
     */
    public function truncateProvider()
    {
        return [
            ['Hello foo bar', 5, 'Hello'],
            ['Hello foo bar', 5, 'Hello...', '...'],
            ['Hello foo bar', 6, 'Hello ...', '...'],
            ['Hello foo bar', 8, 'Hello fo...', '...'],
            ['Hello foo bar', 8, 'Hello...', '...', false],
            ['Hello foo bar', 13, 'Hello foo bar'],
            ['Hello foo bar', 15, 'Hello foo bar'],
            ['Hello foo bar', 15, 'Hello foo bar'],
            ['白日依山盡 黃河入海流', 4, '白日依山'],
            ['白日依山盡 黃河入海流', 4, '白日依山...', '...'],
        ];
    }

    /**
     * testMap
     *
     * @return  void
     */
    public function testMap()
    {
        $actual = Str::map(
            'Foo/Bar/Yoo',
            function ($char, $key) {
                return $char === '/' ? '_' : $char;
            }
        );

        self::assertEquals('Foo_Bar_Yoo', (string) $actual);

        $actual = Str::map('Foo/Bar/Yoo', 'strtoupper');

        self::assertEquals('FOO/BAR/YOO', (string) $actual);
    }

    /**
     * testFilter
     *
     * @return  void
     */
    public function testFilter()
    {
        $actual = Str::filter(
            'Foo/Bar/Yoo',
            function ($char, $key) {
                return $char !== '/';
            }
        );

        self::assertEquals('FooBarYoo', (string) $actual);

        $actual = Str::filter('Foo1Bar2Yoo', 'is_numeric');

        self::assertEquals('12', (string) $actual);
    }

    /**
     * testReject
     *
     * @return  void
     */
    public function testReject()
    {
        $actual = Str::reject(
            'Foo/Bar/Yoo',
            function ($char, $key) {
                return $char === '/';
            }
        );

        self::assertEquals('FooBarYoo', (string) $actual);

        $actual = Str::reject('Foo1Bar2Yoo', 'is_numeric');

        self::assertEquals('FooBarYoo', (string) $actual);
    }
}
