<?php
/**
 * @author nesfoubaer
 * @date 16/6/12 上午11:53
 */

namespace niceforbear\jdbrbac\commands;

/**
 * Steps:
 *
 * 1. 配置system = md5() // maybe
 * 2. 配置文件夹
 * 3. 递归读取配置的文件夹
 *   * 如果是文件则处理
 *   * 如果是文件夹, 则通过步骤3继续处理
 * 4. 如果是文件:
 *   * 通过文件名判断是否是Controller
 *   * 如果是, 获得命名空间, 获得方法 & 注释
 *   * 判断方法满足筛选条件, 注释满足条件, 写入是否满足写入条件
 *   * 如果库里的方法没有了, 则将库里相关联的路由删除
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
