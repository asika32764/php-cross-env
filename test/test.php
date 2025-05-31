<?php
/**
 * Part of cross-env project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    MIT
 */

echo "HELLO\n\n";

$values = [];

foreach ($_SERVER as $key => $value) {
    if (str_starts_with($key, 'TEST_')) {
        $values[] = "$key=$value\n";
    }
}

sort($values);

array_shift($argv);

foreach ($argv as $k => $v) {
    $values[] = $v . "\n";
}

echo implode('', $values);
