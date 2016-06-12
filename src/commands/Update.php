<?php
/**
 * @author nesfoubaer
 * @date 16/6/12 上午11:53
 */

namespace niceforbear\jdbrbac\commands;

class Update
{
    public static function route()
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
                Common::batchUpdateRoute($routes);
            }
        }

        var_dump('Finish');
        exit;
    }
}
