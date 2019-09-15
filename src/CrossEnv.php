<?php declare(strict_types=1);

namespace CrossEnv;

use Symfony\Component\Process\Process;

/**
 * Part of cross-env project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    MIT
 */
class CrossEnv
{
    const ENV_SETTER_REGEX = '/(\w+)=(\'(.*)\'|"(.*)"|(.*))/';

    public static function runWithCommand(string $command, callable $outputHandler = null): int
    {
        $args = array_filter(array_map('trim', explode(' ', $command)), 'strlen');

        return static::runWithArgs($args, $outputHandler);
    }

    public static function runWithArgs(array $args, callable $outputHandler = null): int
    {
        array_unshift($args, 'cross-env');

        return (new static)->run($args, $outputHandler);
    }

    public function run(array $argv, callable $outputHandler = null): int
    {
        $this->parseArgv($argv, $env, $command);

        $process = new Process($command);
        $process->setTimeout(0);

        if (!static::isWindows()) {
            static::signals(
                [
                    SIGTERM,
                    SIGINT,
                    SIGHUP,
                ],
                static function (int $signal) use ($process) {
                    $process->signal($signal);
                }
            );
        }

        return $process->run($outputHandler ?: static function ($type, $buffer) {
            if (Process::ERR === $type) {
                static::fwrite(STDERR, $buffer);
            } else {
                static::fwrite(STDOUT, $buffer);
            }
        }, $env);
    }

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
        return fwrite($handle, $text);
    }

    public static function signals(array $signals, callable $handler)
    {
        if (!function_exists('pcntl_signal')) {
            return;
        }

        foreach ($signals as $signal) {
            static::pcntlSignal((int) $signal, $handler);
        }
    }

    protected static function pcntlSignal(int $signal, callable $handler): bool
    {
        return pcntl_signal($signal, $handler);
    }

    public static function isWindows(): bool
    {
        return \DIRECTORY_SEPARATOR === '\\';
    }

    protected function parseArgv(array $argv, array &$env = null, string &$command = null)
    {
        $env = [];
        $command = '';

        array_shift($argv);

        $args = [];

        $key = null;

        foreach ($argv as $i => $arg) {
            $matched = (int) preg_match(static::ENV_SETTER_REGEX, $arg, $matches);

            if ($matched) {
                $value = $matches[5] ?? $matches[4] ?? $matches[3];

                $env[$matches[1]] = $value;
            } else {
                $args[] = $arg;
            }
        }

        $command = implode(' ', $args);
    }
}
