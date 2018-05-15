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
    runtime_check();
}

if (!function_exists('command_handle')) {
    // 命令行参数解析
    function command_handle()
    {
        global $argv;
        @list($entry, $command) = array_slice($argv, 0, 2);
        $opt = array();
        $i = empty($command) ? 1 : 2;
        while ($i < count($argv)) {
            $option = str_replace('-', '', $argv[$i], $count);
            $next = isset($argv[$i + 1]) ? str_replace('-', '', $argv[$i + 1], $next_count) : false;
            if ($count === 0) {
                $i = $i + 1;
                continue;
            }
            if ($next !== false && $next_count === 0) {
                $opt[$option] = $next;
                $i = $i + 2;
            } else {
                $opt[$option] = true;
                $i = $i + 1;
            }
        }
        Core\Server::handle($entry, $command, $opt);
    }
    command_handle();
}
