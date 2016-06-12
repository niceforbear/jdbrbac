<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午1:53
 */

namespace niceforbear\jdbrbac\models;

use niceforbear\jdbrbac\helpers\RbacConsts;
use niceforbear\jdbrbac\helpers\RbacUtils;

class RbacUserAssign extends BaseModel
{
    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return RbacConsts::DB_TABLE_PREFIX . '_rbac_user_assign';
    }

    public static function getAllByUserId($userId)
    {
        $rows = self::find()
            ->where('user_id = :ui', [':ui' => $userId])
            ->asArray()
            ->all();
        return $rows;
    }

    public static function addOne($userId, $roleId)
    {
        $model = new RbacUserAssign();
        $model->user_id = $userId;
        $model->role_id = $roleId;
        $model->time_create = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function deleteByUserId($userId)
    {
        return RbacUserAssign::deleteAll('user_id = :ui', [':ui' => $userId]);
    }

    public static function deleteByRoleId($roleId)
    {
        return RbacUserAssign::deleteAll('role_id = :ri', [':ri' => $roleId]);
    }
}
