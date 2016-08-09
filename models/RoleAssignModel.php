<?php

/**
 * @author nesfoubaer
 * @date 16/6/28 下午3:34
 */

namespace app\jdbrbac\models;

use app\jdbrbac\components\JdbRbacException;
use app\jdbrbac\components\Utils;

class RoleAssignModel extends BaseModel
{
    const COL_ROLE_ID = 'role_id';
    const COL_PERMISSION_ID = 'permission_id';

    public static function checkRoleId($roleId)
    {
    }
    public static function checkPermissionId($permissionId)
    {
    }
    public static function checkPermissionIds($permissionIds)
    {
    }

    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return Utils::TABLE_ROLE_ASSIGN;
    }

    public static function addOne($roleId, $permissionId)
    {
        $model = new RoleAssignModel();
        $model->role_id = $roleId;
        $model->permission_id = $permissionId;
        return $model->save();
    }

    public static function addBatch($roleId, $permissionIds)
    {
        $permissionIdArray = explode(',', $permissionIds);
        foreach ($permissionIdArray as $permissionId) {
            $result = self::addOne($roleId, $permissionId);
            if (!$result) {
                throw new JdbRbacException();
            }
        }

        return true;
    }

    public static function getAllByRoleId($id)
    {
        return self::find()
            ->where('role_id = :rid', [':rid' => $id])
            ->asArray()
            ->all();
    }

    public static function deleteAllByRoleId($roleId)
    {
        return RoleAssignModel::deleteAll('role_id = :rid', [':rid' => $roleId]);
    }

    public static function getAllByRoleIdsArray($roleIdsArray){
        foreach($roleIdsArray as &$roleId){
            $roleId = '\''.$roleId.'\'';
        }
        $roleIdsString = '(' . implode(',', $roleIdsArray) . ')';

        return self::find()
            ->where('role_id in'.$roleIdsString)
            ->asArray()
            ->all();
    }
}
