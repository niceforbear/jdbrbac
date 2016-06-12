<?php
/**
 * @author nesfoubaer
 * @date 16/6/12 下午4:21
 */

namespace niceforbear\jdbrbac\commands;

use niceforbear\jdbrbac\models\RbacRoute;

class Common
{
    public static $system = 100;
    public static $controllerKeyWord = 'Controller';
    public static $_sourceData = [
        [
            'dir' => '/data/www/dmp/controllers',
            'namespace' => '\app\\controllers\\',
            'prefix' => '',
        ],
        [
            'dir' => '/data/www/dmp/modules/Cms/controllers',
            'namespace' => '\app\\modules\\Cms\\controllers\\',
            'prefix' => '/cms',
        ]
    ];
    public static function scanFile($file, $namespace, $prefix)
    {
        $routes = [];
        if (strpos($file, self::$controllerKeyWord)) {
            $fileName = explode('.', $file)[0];
            $className = substr($fileName, 0, -10);

            $className = self::parser($className, 'class');
            $className = $prefix . $className;

            $classPath = $namespace . $fileName;
            $methods = get_class_methods($classPath);

            foreach ($methods as $method) {
                $method = self::scanMethod($method);
                if (isset($method)) {
                    $routes[] = $className . $method;
                }
            }
        }
        return $routes;
    }

    public static function scanMethod($method)
    {
        if (strpos($method, 'action') === 0) {
            $ord = ord(substr($method, 6, 1));
            if ($ord > 64 && $ord < 91) {
                return self::parser($method, 'action');
            }
        }

        return null;
    }

    public static function parser($name, $category)
    {
        if ($category == 'action') {
            $method = substr($name, 6);
            $newMethod = '';
            for ($i = 0; $i < strlen($method); $i++) {
                $ord = ord($method[$i]);
                if ($ord > 64 && $ord < 91) {
                    if ($i == 0) {
                        $newMethod .= '/' . strtolower($method[$i]);
                    } else {
                        $newMethod .= '-' . strtolower($method[$i]);
                    }
                } else {
                    $newMethod .= $method[$i];
                }
            }
            return $newMethod;
        } elseif ($category == 'class') {
            $newClassName = '';
            for ($i = 0; $i < strlen($name); $i++) {
                $ord = ord($name[$i]);
                if ($ord > 64 && $ord < 91) {
                    if ($i == 0) {
                        $newClassName .= '/' . strtolower($name[$i]);
                    } else {
                        $newClassName .= '-' . strtolower($name[$i]);
                    }
                } else {
                    $newClassName .= $name[$i];
                }
            }
            return $newClassName;
        } else {
            echo 'Wrong category';
            exit;
        }
    }

    public static function batchAddRoute($routes)
    {
        if (empty($routes)) {
            return true;
        }

        foreach ($routes as $route) {
            echo $route . "<br>" . PHP_EOL;
            RbacRoute::addOne('', $route, self::$system);
        }

        return true;
    }

    public static function batchUpdateRoute($routes)
    {
        if (empty($routes)) {
            return true;
        }

        foreach ($routes as $route) {
            echo $route . "<br>" . PHP_EOL;
            $isExist = RbacRoute::isRouteExist($route);
            if (!$isExist) {
                RbacRoute::addOne('', $route, self::$system);
            }
        }

        return true;
    }
}
