<?php

/**
 * @author nesfoubaer
 * @date 16/6/28 下午3:34
 */

namespace app\jdbrbac\models;

use app\jdbrbac\components\Utils;

class RoleModel extends BaseModel
{
    const COL_ID = 'id';
    const COL_NAME = 'name';
    const COL_EXT = 'ext';

    public static function checkId($id)
    {
    }
    public static function checkName($name)
    {
    }

    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return Utils::TABLE_ROLE;
    }

    public static function getRole()
    {
        return self::find()
            ->asArray()
            ->all();
    }

    public static function addOne($name)
    {
        $model = new RoleModel();
        $model->name = $name;
        return $model->save();
    }

    public static function getOneById($id)
    {
        return self::find()
            ->where('id = :id', [':id' => $id])
            ->asArray()
            ->one();
    }

    public static function getOneByName($name)
    {
        return self::find()
            ->where('name = :name', [':name' => $name])
            ->asArray()
            ->one();
    }

    public static function getOneIdByName($name)
    {
        $data = self::getOneByName($name);
        return $data[self::COL_ID];
    }

    public static function updateOneRole($id, $name)
    {
        $model = RoleModel::findOne($id);
        $model->name = $name;
        return $model->save();
    }

    public static function deleteOne($roleId)
    {
        return RoleModel::deleteAll('id = :id', [':id' => $roleId]);
    }
}
