<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午1:52
 */

namespace niceforbear\jdbrbac\models;

use niceforbear\jdbrbac\helpers\RbacConsts;
use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\helpers\RbacUtils;

class RbacPermission extends BaseModel
{
    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return RbacConsts::DB_TABLE_PREFIX . '_rbac_permission';
    }

    public static function getOneByPermissionId($permId)
    {
        $row = self::find()
            ->where('id = :id', [':id' => $permId])
            ->asArray()
            ->one();
        return $row;
    }

    public static function getAllByPermissionIds(array $permIds)
    {
        if (is_array($permIds)) {
            foreach ($permIds as $k => $permId) {
                $permIds[$k] = '\'' . $permId . '\'';
            }

            $idString = implode(',', $permIds);
            $where = "id in ({$idString})";
        } else {
            throw new RbacException('Invalid role ids in jdbrbac.RbacPermission', 1000050033);
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

    public static function addOne($permName)
    {
        $model = new RbacPermission();
        $model->name = $permName;
        $model->time_create = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function getByPermissionName($permName)
    {
        $row = self::find()
            ->where('name = :n', [':n' => $permName])
            ->asArray()
            ->one();
        return $row;
    }

    public static function deleteByPermissionId($permId)
    {
        return RbacPermission::deleteAll('id = :pi', [':pi' => $permId]);
    }

    public static function updateOne($permId, $permName)
    {
        $model = self::findOne($permId);
        $model->name = $permName;
        $model->time_update = RbacUtils::getDate(time());
        return $model->save();
    }

    public static function isPermissionNameOccupied($permName)
    {
        $result = self::find()
            ->where('name = :pn', [':pn' => $permName])
            ->exists();
        return $result;
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
}
