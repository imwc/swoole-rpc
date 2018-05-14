<?php
/**
 * 引导程序
 */
require_once __DIR__ . '/loader.php';

Core\Loader::set_namespace('Core', __DIR__);
spl_autoload_register('\\Core\\Loader::autoload');
