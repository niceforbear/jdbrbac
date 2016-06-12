<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午3:39
 */

namespace niceforbear\jdbrbac\models;

use niceforbear\jdbrbac\helpers\RbacUtils;
use niceforbear\jdbrbac\helpers\RbacConsts;

class RbacRoute extends BaseModel
{
    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return RbacConsts::DB_TABLE_PREFIX . '_rbac_route';
    }

    public static function getAll()
    {
        $rows = self::find()
            ->asArray()
            ->all();
        return $rows;
    }

    public static function getByIds(array $routeIds)
    {
        foreach ($routeIds as $k => $routeId) {
            $roleIds[$k] = '\'' . $routeId . '\'';
        }

        $idString = implode(',', $routeIds);
        $where = "id in ({$idString})";

        $rows = self::find()
            ->where($where)
            ->asArray()
            ->all();
        return $rows;
    }

    public static function getOneById($id)
    {
        $rows = self::find()
            ->where('id = :id', [':id' => $id])
            ->asArray()
            ->one();
        return $rows;
    }

    public static function addOne($routeName, $route, $system)
    {
        $model = new RbacRoute();
        $model->name = $routeName;
        $model->route = $route;
        $model->system = $system;
        $model->time_create = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function deleteByRouteId($routeId)
    {
        return RbacRoute::deleteAll('id = :id', [':id' => $routeId]);
    }

    public static function updateOne($routeId, $routeName, $route, $system)
    {
        $model = self::findOne($routeId);
        $model->name = $routeName;
        $model->route = $route;
        $model->system = $system;
        $model->time_update = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function getBatch($page, $condition)
    {
        $limit = RbacConsts::PAGINATION_LIMIT;
        $rows = self::find()
            ->where($condition)
            ->offset($page * $limit)
            ->limit($limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        return $rows;
    }

    public static function isRouteExist($route)
    {
        $result = self::find()
            ->where('route = :r', [':r' => $route])
            ->exists();
        return $result;
    }
}
