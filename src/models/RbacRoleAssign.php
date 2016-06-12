<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午1:53
 */

namespace niceforbear\jdbrbac\models;

use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\helpers\RbacConsts;
use niceforbear\jdbrbac\helpers\RbacUtils;

class RbacRoleAssign extends BaseModel
{
    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return RbacConsts::DB_TABLE_PREFIX . '_rbac_role_assign';
    }

    public static function getAllByRoleIds(array $roleIds)
    {
        if (is_array($roleIds)) {
            foreach ($roleIds as $k => $roleId) {
                $roleIds[$k] = '\'' . $roleId . '\'';
            }

            $idString = implode(',', $roleIds);
            $where = "role_id in ({$idString})";
        } else {
            throw new RbacException('Invalid role ids in jdbrbac.RbacRoleAssign', 1000050033);
        }

        $rows = self::find()
            ->where($where)
            ->asArray()
            ->all();

        return $rows;
    }

    public static function addOne($roleId, $permId)
    {
        $model = new RbacRoleAssign();
        $model->role_id = $roleId;
        $model->perm_id = $permId;
        $model->time_create = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function deleteByRoleId($roleId)
    {
        return RbacRoleAssign::deleteAll('role_id = :ri', [':ri' => $roleId]);
    }
}
