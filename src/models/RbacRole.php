<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午1:53
 */

namespace niceforbear\jdbrbac\models;

use niceforbear\jdbrbac\helpers\RbacConsts;
use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\helpers\RbacUtils;

class RbacRole extends BaseModel
{
    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return RbacConsts::DB_TABLE_PREFIX . '_rbac_role';
    }

    public static function getOneByRoleId($roleId)
    {
        $rows = self::find()
            ->where('id = :id', [':id' => $roleId])
            ->asArray()
            ->one();
        return $rows;
    }

    public static function getAllByRoleIds(array $roleIds)
    {
        if (is_array($roleIds)) {
            foreach ($roleIds as $k => $roleId) {
                $roleIds[$k] = '\'' . $roleId . '\'';
            }

            $idString = implode(',', $roleIds);
            $where = "id in ({$idString})";
        } else {
            throw new RbacException('Invalid role ids in jdbrbac.RbacRole', 1000050028);
        }

        $rows = self::find()
            ->where($where)
            ->asArray()
            ->all();

        return $rows;
    }

    public static function getAll()
    {
        $rows = self::find()
            ->asArray()
            ->all();
        return $rows;
    }

    public static function isRoleNameOccupied($roleName)
    {
        $result = self::find()
            ->where('role_name = :rn', [':rn' => $roleName])
            ->exists();
        return $result;
    }

    public static function addOne($roleName)
    {
        $model = new RbacRole();
        $model->role_name = $roleName;
        $model->time_create = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function getOneByRoleName($roleName)
    {
        $rows = self::find()
            ->where('role_name = :rn', [':rn' => $roleName])
            ->asArray()
            ->one();
        return $rows;
    }

    public static function deleteById($id)
    {
        return RbacRole::deleteAll('id = :id', [':id' => $id]);
    }

    public static function updateById($id, $roleName)
    {
        $model = self::findOne($id);
        $model->role_name = $roleName;
        $model->time_update = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function getByBatch($page, $condition)
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
}
