<?php
namespace Core;

class Loader
{

    /**
	 * 命名空间的路径
	 */
    private static $namespaces = array();

    /**
	 * 自动载入类
	 * @param $class
	 */
	static function autoload($class)
    {
        $root = explode('\\', trim($class, '\\'), 2);
        if (count($root) > 1 and isset(self::$namespaces[$root[0]])) {
            include self::$namespaces[$root[0]] . '/' . str_replace('\\', '/', $root[1]) . '.php';
		}
    }
    
    /**
	 * 设置命名空间路径
	 * @param $root
	 * @param $path
	 */
	static function setNamespace($root, $path)
    {
		self::$namespaces[$root] = $path;
	}

}
