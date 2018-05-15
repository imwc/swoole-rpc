<?php
namespace Core;

class Application
{
    /**
     * 项目根目录
     * @var Path
     */
    public static $root_path = ROOT;

    /**
     * 应用实例
     * @var Instance
     */
    private static $_app;

     /**
     * 配置
     * @var config
     */
    public $config;

    private function __construct()
    {
        if (empty(self::$root_path)) {
            throw new \Exception("empty root path", 1);
        }

        // 载入配置
        require_once(ROOT . '/config/config.php');
        $this->config = &$configs;
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
        $swoole_config = isset($this->config['swoole']) ? $this->config['swoole'] : array();
        $server = new Server($this->config['host'], $this->config['port']);
        $server->run($swoole_config);
    }
}
