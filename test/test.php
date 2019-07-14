<?php
/**
 * Part of cross-env project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    MIT
 */

echo "HELLO\n\n";

foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'TEST_') === 0) {
        echo "$key=$value\n";
    }
}
