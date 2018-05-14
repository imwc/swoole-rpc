<?php
namespace Core;

use Core\Application;

/**
* Server - 服务管理
*/
class Server
{
    private static $pid_file;
    private $server;

    /**
     * 设置PID文件
     * @param $file_path
     */
    public static function setPidFile($file_path)
    {
        self::$pid_file = $file_path;
    }

    /**
     * 命令行指令
     * @param $command
     */
    public static function handle($entry, $command)
    {
        if (empty(self::$pid_file)) {
            self::setPidFile(Application::$root_path . '/swoole_rpc.pid');
        }
        if (is_file(self::$pid_file)) {
            $pid = file_get_contents(self::$pid_file);
        } else {
            $pid = 0;
        }

        if (empty($command)) {
            exit("Usage: php {$entry} {start|stop|reload}" . PHP_EOL);
        } elseif ($command === 'reload') {
            if (empty($pid)) {
                exit('Info: no server is running' . PHP_EOL);
            }
        }
    }

    function __construct($host, $port)
    {
        $this->server = new \swoole_server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
    }
}
