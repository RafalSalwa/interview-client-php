<?php

declare(strict_types=1);

namespace App\Tests;

use function dirname;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

require_once __DIR__ . '/functions.php';

return getAppKernel();
