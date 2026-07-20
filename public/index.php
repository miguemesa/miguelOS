<?php
declare(strict_types=1);

use App\Core\Application;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = new Application(dirname(__DIR__));
$app->run();
