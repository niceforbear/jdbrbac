<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午1:52
 */

namespace niceforbear\jdbrbac\models;

use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\helpers\RbacConsts;
use niceforbear\jdbrbac\helpers\RbacUtils;

class RbacPermissionAssign extends BaseModel
{
    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return RbacConsts::DB_TABLE_PREFIX . '_rbac_permission_assign';
    }

    public static function getAllByPermIds(array $permIds)
    {
        if (is_array($permIds)) {
            foreach ($permIds as $k => $permId) {
                $permIds[$k] = '\'' . $permId . '\'';
            }

            $idString = implode(',', $permIds);
            $where = "perm_id in ({$idString})";
        } else {
            throw new RbacException('Invalid permission ids in jdbrbac.RbacPermissionAssign', 1000050034);
        }

        $rows = self::find()
            ->where($where)
            ->asArray()
            ->all();

        return $rows;
    }

    public static function addOne($permId, $routeId)
    {
        $model = new RbacPermissionAssign();
        $model->permission_id = $permId;
        $model->route_id = $routeId;
        $model->time_create = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function deleteByPermissionId($permId)
    {
        return RbacPermissionAssign::deleteAll('permission_id = :pi', [':pi' => $permId]);
    }
}
