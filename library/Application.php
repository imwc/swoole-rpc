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

    /**
     * 操作系统实例
     * @return os
     */
    public static function os()
    {

    }

    public static function run()
    {
        global $argv;
        @list($entry, $command) = $argv;
        Server::handle($entry, $command);
    }
}
