<?php

use Dengpju\PhpScanner\Config;
use Dengpju\PhpScanner\Scanner;

$config = new Config();
$config->scanRoot = "app\controllers";
$config->excludeNamespace = [
    "app\controllers\BaseController",
    "app\controllers\DemoController",
    "app\controllers\admin\*"
];


(new Scanner($config))->scaner();
