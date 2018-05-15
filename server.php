<?php
define('DEBUG', 'on');
define('ROOT', __DIR__);

//包含框架入口文件
require __DIR__ . '/library/Bootstrap.php';

Core\Application::getInstance()->run();
