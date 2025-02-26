# PHP cross-env

![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/asika32764/php-cross-env/ci.yml?style=for-the-badge)
[![Packagist Version](https://img.shields.io/packagist/v/asika/cross-env?style=for-the-badge)
](https://packagist.org/packages/asika/cross-env)
[![Packagist Downloads](https://img.shields.io/packagist/dt/asika/cross-env?style=for-the-badge)](https://packagist.org/packages/asika/cross-env)

Cross platform setting of environment scripts for PHP.

## Installation

Install in project

```bash
composer require asika/cross-env
```

Install globally

```bash
composer global require asika/cross-env
```

## Usage

### Global Install

Just call `cross-env`:

```bash
cross-env APP_ENV=dev TEST_MODE=real php my-code.php
```

### In Project

If you install it in project, use composer scripts:

```json
{
    ...
    "scripts": {
        "build:dev": "cross-env APP_ENV=dev TEST_MODE=real php my-code.php"
    },
    ...
}
```

Then call it by composer

```bash
composer build:dev

# OR

composer run build:dev
```

You can also call bin file directly:

```bash
./vendor/bin/cross-env APP_ENV=dev TEST_MODE=real php my-code.php
```

See https://getcomposer.org/doc/articles/scripts.md

### Alias

If you have installed node `cross-env` and has a prior order in PATH, 
you can use `set-env` as an global alias.

### Use `.env` File

Call `cross-source` to set a file as env vars.

```bash
cross-source /path/.env php my-code.php
```

## Programmatically Call

If you want to use `cross-env` in your own CLI Application, you can use `CrossEnv\CrossEnv`:

```php
$returnCode = \CrossEnv\CrossEnv::runWithCommand('APP_ENV=dev TEST_MODE=real php my-code.php');

// OR

$returnCode = \CrossEnv\CrossEnv::runWithArgs([
    'APP_ENV=dev',
    'TEST_MODE=real',
    'php',
    'my-code.php'
);
```

### Custom Output

Add second argument as a callable.

```php
use Symfony\Component\Process\Process;

\CrossEnv\CrossEnv::runWithCommand(
    'APP_ENV=dev TEST_MODE=real php my-code.php',
    function (string $type, string $buffer) {
        if ($type === Process::ERR) {
            // Handle error
        } else {
            // Handle output
        }
    }
);
```

See Symfony/Process: https://symfony.com/doc/current/components/process.html#usage
