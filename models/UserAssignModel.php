<?php

/**
 * @author nesfoubaer
 * @date 16/6/28 下午3:34
 */

namespace app\jdbrbac\models;

use app\jdbrbac\components\Utils;
use app\jdbrbac\components\JdbRbacException;

class UserAssignModel extends BaseModel
{
    const COL_ID = 'id';
    const COL_USER_ID = 'user_id';
    const COL_ROLE_ID = 'role_id';
    const COL_TIME_CREATE = 'time_create';

    public static function checkUserId($userId)
    {
    }
    public static function checkRoleIds($roleIds)
    {
    }

    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return Utils::TABLE_USER_ASSIGN;
    }

    public static function getUserAssign()
    {
        return self::find()
            ->asArray()
            ->all();
    }

    public static function addOne($userId, $roleId)
    {
        $model = new UserAssignModel();
        $model->user_id = $userId;
        $model->role_id = $roleId;
        $model->time_create = Utils::getNow();
        return $model->save();
    }

    public static function addBatch($userId, $roleIds)
    {
        $roleIdArray = explode(',', $roleIds);
        foreach ($roleIdArray as $roleId) {
            $result = self::addOne($userId, $roleId);
            if (!$result) {
            }
        }

        return true;
    }

    public static function getAllByUserId($userId)
    {
        return self::find()
            ->where('user_id = :uid', [':uid' => $userId])
            ->asArray()
            ->all();
    }

    public static function deleteAllByUserId($userId)
    {
        return UserAssignModel::deleteAll('user_id = :uid', [':uid' => $userId]);
    }
}
