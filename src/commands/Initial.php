<?php
/**
 * @author nesfoubaer
 * @date 16/6/12 上午11:53
 */

namespace niceforbear\jdbrbac\commands;

/**
 * Steps:
 *
 * 1. 读取配置文件夹下的文件
 * 2. 判断文件是否是controller, 如果是则进行处理
 * 3. 获取文件中action方法
 * 4. 把该action方法解析成route, 并进行存储
 */
class Initial
{
    public function route()
    {
        var_dump('Start');
        foreach (Common::$_sourceData as $sourceData) {
            if (!isset($sourceData['namespace']) || empty($sourceData['namespace'])
                || !isset($sourceData['prefix'])) {
                echo "Wrong format";
                exit;
            }

            if (!is_dir($sourceData['dir'])) {
                echo 'Dir is not dir';
                exit;
            }

            $files = scandir($sourceData['dir']);
            foreach ($files as $file) {
                $routes = Common::scanFile($file, $sourceData['namespace'], $sourceData['prefix']);
                Common::batchAddRoute($routes);
            }
        }

        var_dump('Finish');
        exit;
    }
}
