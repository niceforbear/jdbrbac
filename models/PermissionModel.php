<?php

/**
 * @author nesfoubaer
 * @date 16/6/28 下午3:34
 */

namespace app\jdbrbac\models;

use app\jdbrbac\components\Utils;

class PermissionModel extends BaseModel
{
    const COL_ID = 'id';
    const COL_NAME = 'name';
    const COL_EXT = 'ext';

    public static function checkName($name)
    {
    }
    public static function checkId($id)
    {
    }

    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return Utils::TABLE_PERMISSION;
    }

    public static function deleteByRouteId($routeId)
    {
        return PermissionModel::deleteAll('route_id = :rid', [':rid' => $routeId]);
    }

    public static function getPermission()
    {
        return self::find()
            ->asArray()
            ->all();
    }

    public static function addOnePermission($name)
    {
        $model = new PermissionModel();
        $model->name = $name;
        return $model->save();
    }

    public static function getOnePermission($id)
    {
        return self::find()
            ->where('id = :id', [':id' => $id])
            ->asArray()
            ->one();
    }

    public static function getOnePermissionIdByName($name)
    {
        $data = self::find()
            ->where('name = :n', [':n' => $name])
            ->asArray()
            ->one();
        return $data[self::COL_ID];
    }

    public static function updateOnePermission($id, $name)
    {
        $model = PermissionModel::findOne($id);
        $model->name = $name;
        return $model->save();
    }

    public static function deleteOnePermission($id)
    {
        return PermissionModel::deleteAll('id = :id', [':id' => $id]);
    }
}
