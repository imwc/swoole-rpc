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
    private $host;
    private $port;

    /**
     * 服务器设置
     * @var $setting
     */
    private $setting = array();

    const PACKMAX_LENGTH        = 2465792; // 2M默认最大长度
    const HEADER_SIZE           = 16;
    const HEADER_STRUCT         = "Nlength/Ntype/Nuid/Nserid";
    const HEADER_PACK           = "NNNN";

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
    public static function handle($entry, $command, $options)
    {
        if (empty(self::$pid_file)) {
            self::setPidFile(Application::$root_path . '/swoole_rpc.pid');
        }
        if (is_file(self::$pid_file)) {
            $pid = file_get_contents(self::$pid_file);
        } else {
            $pid = 0;
        }

        if ($command === 'reload') {
            if (empty($pid)) {
                exit('Info: no server is running' . PHP_EOL);
            }
        } elseif ($command === 'stop') {
            if (empty($pid)) {
                exit('Info: no server is running' . PHP_EOL);
            }
        } elseif ($command === 'start') {
            if (!empty($pid)) {
                exit('Info: server is already running' . PHP_EOL);
            }
        } else {
            exit("Usage: php {$entry} {start|stop|reload}" . PHP_EOL);
        }
    }

    function __construct($host, $port)
    {
        $this->port = $port;
        $this->host = $host;
        $this->server = new \swoole_server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
        // 默认的服务器配置
        $this->setting = array(
            'worker_num' => 1,
            'daemonize' => 0,
            'max_request' => 10000,
            'dispatch_mode' => 3, // 分发策略 https://wiki.swoole.com/wiki/page/277.html
            'open_length_check' => true, // 打开包长检测特性 https://wiki.swoole.com/wiki/page/287.html
            'package_max_length' => 2465792, // 2M默认最大长度
            'package_length_type' => 'N',
            'package_body_offset' => self::HEADER_SIZE,
            'package_length_offset' => 0,
        );
    }

    function run($setting = array())
    {
        $this->setting = array_merge($this->setting, $setting);
        if (self::$pid_file) {
            $this->setting['pid_file'] = self::$pid_file;
        }
        $this->server->set($this->setting);
        $this->server->on('ManagerStart', function ($serv) {
            $this->setProcessName($this->getProcessName() . ': manager');
        });

        // 回调映射
        $this->server->on('Start', array($this, 'onMasterStart'));
        $this->server->on('Shutdown', array($this, 'onMasterStop'));
        $this->server->on('ManagerStop', array($this, 'onManagerStop'));
        $this->server->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->server->on('Connect', array($this, 'onConnect'));
        $this->server->on('Close', array($this, 'onClose'));
        $this->server->on('Receive', array($this, 'onReceive'));
        $this->server->on('WorkerStop', array($this, 'onWorkerStop'));

        // 启动服务
        $this->server->start();
    }

    /**
     * 设置进程的名称
     * @param $name
     */
    private function setProcessName($name)
    {
        if (function_exists('cli_set_process_title')) {
            @cli_set_process_title($name);
        } else {
            if (function_exists('swoole_set_process_name')) {
                @swoole_set_process_name($name);
            } else {
                trigger_error(__METHOD__ . " failed. require cli_set_process_title or swoole_set_process_name.");
            }
        }
    }

    /**
     * 获取进程名称
     * @return string
     */
    private function getProcessName()
    {
        global $argv;
        return "php {$argv[0]}";
    }

    public function onMasterStart($serv)
    {
        $this->setProcessName($this->getProcessName() . ': master -host=' . $this->host . ' -port=' . $this->port);
        if (!empty($this->setting['pid_file'])) {
            file_put_contents(self::$pid_file, $serv->master_pid);
        }
    }

    public function onMasterStop($serv)
    {
        if (!empty($this->setting['pid_file'])) {
            unlink(self::$pid_file);
        }
    }

    public function onWorkerStart($serv, $worker_id)
    {
        /**
         * 清理Opcache缓存
         */
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        /**
         * 清理APC缓存
         */
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        }

        if ($worker_id >= $serv->setting['worker_num']) {
            $this->setProcessName($this->getProcessName() . ': task');
        } else {
            $this->setProcessName($this->getProcessName() . ': worker');
        }
    }

    public function onWorkerStop($serv, $worker_id)
    {

    }

    public function onConnect($serv, $client_id, $from_id)
    {

    }

    public function onReceive($serv, $client_id, $from_id, $data)
    {

    }

    public function onClose($serv, $client_id, $from_id)
    {

    }

    public function onManagerStop()
    {

    }

    public function onRequest()
    {

    }
}
