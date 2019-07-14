# PHP cross-env

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

Just call `cross-env` (If install globally):

```bash
cross-env APP_ENV=dev TEST_MODE=real php my-code.php
```

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

See https://getcomposer.org/doc/articles/scripts.md

### Alias

If you have installed node `cross-env` and has a prior order in PATH, 
you can use `set-env` as an global alias.

### Use `.env` File

Call `cross-source` to set a file as env vars.

```bash
cross-course /path/.env php my-code.php
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

\CrossEnv\CrossEnv::runWithCommand('...', function (string $type, string $buffer) {
    if ($type === Process::ERR) {
        // Handle error
    } else {
        // Handle output
    }
})
```

See Symfony/Process: https://symfony.com/doc/current/components/process.html#usage
