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
    if (strpos($key, 'TEST_') === 0) {
        $values[] = "$key=$value\n";
    }
}

sort($values);

echo implode('', $values);
