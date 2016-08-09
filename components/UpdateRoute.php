<?php
/**
 * @author nesfoubaer
 * @date 16/6/30 下午5:30
 */

namespace app\jdbrbac\components;

use app\jdbrbac\models\RouteModel;

class UpdateRoute
{
    private static $controllerKeyWord = 'Controller';
    private static $actionKeyword = 'action';
    private static $classKeyword = 'class';

    public static function checkSourceData($sourceData)
    {
        if (!is_array($sourceData)) {
        }

        if (!isset($sourceData['namespace']) || empty($sourceData['namespace'])) {
        }

        if (!isset($sourceData['prefix']) || empty($sourceData['prefix'])) {
        }

        if (!is_dir($sourceData['dir'])) {
        }
    }

    public static function scanFile($file, $namespace, $prefix)
    {
        if (is_dir($file)) {
            return null;
        }

        $routes = [];

        if (strpos($file, self::$controllerKeyWord)) {
            $fileName = explode('.', $file)[0];
            $className = substr($fileName, 0, -10);

            $className = self::parser($className, self::$classKeyword);
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

    public static function batchAddRoute($routes)
    {
        if (!isset($routes) || empty($routes)) {
            return true;
        }

        foreach ($routes as $route) {
            if (RouteModel::isExistByRoute($route) == true) {
                Utils::dump('Added: '.$route);
            } else {
                RouteModel::addOneSystemRoute($route);
                Utils::dump('New add: '.$route);
            }
        }

        return true;
    }

    public static function parser($name, $category)
    {
        if ($category == self::$actionKeyword) {
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
        } elseif ($category == self::$classKeyword) {
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
            Utils::dump('Wrong category. ');
            exit;
        }
    }

    public static function scanMethod($method)
    {
        if (strpos($method, self::$actionKeyword) === 0) {
            $ord = ord(substr($method, 6, 1));
            if ($ord > 64 && $ord < 91) {
                return self::parser($method, self::$actionKeyword);
            }
        }

        return null;
    }
}
