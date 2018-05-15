<?php
namespace Core;

class Application
{
    /**
     * 项目根目录
     * @var Path
     */
    public static $root_path;

    /**
     * 应用实例
     * @var Instance
     */
    private static $_app;

    private function __construct()
    {
        if (defined('ROOT')) {
            self::$root_path = ROOT;
        }

        if (empty(self::$root_path)) {
            throw new \Exception("empty root path", 1);
        }
    }

    /**
     * 初始化
     * @return Application
     */
    public static function getInstance()
    {
        if (!self::$_app) {
            self::$_app = new self();
        }
        return self::$_app;
    }

    public function run()
    {
        $server = new Server('0.0.0.0', 8888);
        // $server->run(
        //     array(
        //         'worker_num' => 4,
        //         'max_request' => 5000,
        //         'dispatch_mode' => 3,
        //         'open_length_check' => 1,
        //         'package_max_length' => $AppSvr->packet_maxlen,
        //         'package_length_type' => 'N',
        //         'package_body_offset' => \Swoole\Protocol\RPCServer::HEADER_SIZE,
        //         'package_length_offset' => 0,
        //     )
        // );
    }
}
