<?php

/**
 * @author nesfoubaer
 * @date 16/6/28 下午3:33
 */

namespace app\jdbrbac\models;

use app\jdbrbac\components\JdbRbacException;
use app\jdbrbac\components\Utils;

/**
 * -- if type == 1: ext=json_encode(['method'=>'', 'params' => ['k1' => 'v1', 'k2' => 'v2', ...]]);
 */
class RouteModel extends BaseModel
{
    const COL_ID = 'id';
    const COL_ROUTE = 'route';
    const COL_TYPE = 'type';
    const COL_TYPE_SYSTEM = 0;
    const COL_TYPE_CUSTOM = 1;
    const COL_EXT_METHOD = 'method';
    const COL_EXT_PARAMS = 'params';

    public static function checkId($id)
    {
        self::baseCheckTableId($id);
    }

    public static function checkRoute($route)
    {
    }
    public static function checkParams($method, $params)
    {
    }

    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return Utils::TABLE_ROUTE;
    }

    public static function getAllRoute()
    {
        $data = self::find()
            ->asArray()
            ->all();

        foreach ($data as &$item) {
            if ($item['type'] == self::COL_TYPE_CUSTOM) {
                $item['ext'] = json_decode($item['ext'], true);
            }
        }

        return $data;
    }

    public static function getSystemRoute()
    {
        return self::find()
            ->where('type = :type', [':type' => self::COL_TYPE_SYSTEM])
            ->asArray()
            ->all();
    }

    public static function getCustomRoute()
    {
        $data = self::find()
            ->where('type = :type', [':type' => self::COL_TYPE_CUSTOM])
            ->asArray()
            ->all();

        foreach ($data as &$item) {
            $item['ext'] = json_decode($item['ext'], true);
        }

        return $data;
    }

    public static function addOneSystemRoute($route)
    {
        $model = new RouteModel();
        $model->route = $route;
        $model->type = self::COL_TYPE_SYSTEM;
        return $model->save();
    }

    public static function isExistByRoute($route)
    {
        return self::find()
            ->where('route = :route', [':route' => $route])
            ->exists();
    }

    public static function addOneCustomRoute($route, $method, $params)
    {
        $model = new RouteModel();
        $model->route = $route;
        $model->type = self::COL_TYPE_CUSTOM;
        $model->ext = json_encode([
            'method' => $method,
            'params' => $params
        ]);
        return $model->save();
    }

    public static function getOneCustomRoute($id)
    {
        $data = self::find()
            ->where('id = :id', [':id' => $id])
            ->asArray()
            ->one();
        $data['ext'] = json_decode($data['ext'], true);
        return $data;
    }

    public static function updateOneCustomRoute($id, $route, $method, $params)
    {
        $model = RouteModel::findOne($id);
        $model->route = $route;
        $model->ext = json_encode([
            'method' => $method,
            'params' => $params
        ]);
        return $model->save();
    }

    public static function deleteOneCustomRoute($id)
    {
        $data = self::getOneCustomRoute($id);
        if ($data['type'] == self::COL_TYPE_CUSTOM) {
            return RouteModel::deleteAll('id = :id', [':id' => $id]);
        } else {
            throw new JdbRbacException('You are deleting system route. It is forbidden', 40312493);
        }
    }

    public static function getAllByIdsArray($routeIdsArray)
    {
        foreach($routeIdsArray as &$routeId) {
            $routeId = '\'' . $routeId . '\'';
        }
        $routeIdsString = '('.implode(',', $routeIdsArray).')';

        return self::find()
            ->where('id in '.$routeIdsString)
            ->asArray()
            ->all();
    }
}
