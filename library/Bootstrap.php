<?php
/**
 * 引导程序
 */
require_once __DIR__ . '/loader.php';

Core\Loader::setNamespace('Core', __DIR__);
spl_autoload_register('\\Core\\Loader::autoload');

if (!function_exists('runtime_check')) {
    function runtime_check()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // 不支持windows平台
            exit('Error: no support for Windows' . PHP_EOL);
        }
    }
}

runtime_check();
