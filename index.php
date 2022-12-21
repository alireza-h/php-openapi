<?php

use AlirezaH\OpenApiGenerator\Example\ExampleAPIDocumentGenerator;

require __DIR__ . '/vendor/autoload.php';

if (isset($_GET['swagger'])) {
    require_once __DIR__ . '/resources/templates/swagger.html';

    die();
}

header('Content-Type: application/json; charset=utf-8');

echo (new ExampleAPIDocumentGenerator())->docs();

die();
