<?php
/**
 * Part of cross-env project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    MIT
 */

namespace CrossEnv\Test;

use CrossEnv\CrossEnv;

/**
 * The StubCrossEnv class.
 *
 * @since  __DEPLOY_VERSION__
 */
class StubCrossEnv extends CrossEnv
{
    public static $signals = [];

    public static $output = '';

    /**
     * fwrite() for test use.
     *
     * @param resource $handle
     * @param string   $text
     *
     * @return  bool|int
     */
    protected static function fwrite($handle, string $text)
    {
        static::$output .= $text;

        return true;
    }

    protected static function pcntlSignal(int $signal, callable $handler): bool
    {
        static::$signals[$signal] = $handler;

        return true;
    }

    public static function reset()
    {
        static::$signals = [];
        static::$output = '';
    }
}
