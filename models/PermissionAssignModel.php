<?php

/**
 * @author nesfoubaer
 * @date 16/6/28 下午3:34
 */

namespace app\jdbrbac\models;

use app\jdbrbac\components\JdbRbacException;
use app\jdbrbac\components\Utils;

class PermissionAssignModel extends BaseModel
{
    const COL_ID = 'id';
    const COL_PERMISSION_ID = 'permission_id';
    const COL_ROUTE_ID = 'route_id';

    public static function checkPermissionId($permissionId)
    {
    }
    public static function checkRouteIds($routeIds)
    {
    }

    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return Utils::TABLE_PERMISSION_ASSIGN;
    }

    public static function addOne($permissionId, $routeId)
    {
        $model = new PermissionAssignModel();
        $model-> permission_id = $permissionId;
        $model->route_id = $routeId;
        return $model->save();
    }

    public static function addBatch($permissionId, $routeIds)
    {
        $routeIdArray = explode(',', $routeIds);
        foreach ($routeIdArray as $routeId) {
            $result = self::addOne($permissionId, $routeId);
            if (!$result) {
                throw new JdbRbacException('Add permission assign fail: '.json_encode([$permissionId, $routeId]), 5004677);
            }
        }

        return true;
    }

    public static function getAllByPermissionId($id)
    {
        return self::find()
            ->where('permission_id = :pid', [':pid' => $id])
            ->asArray()
            ->all();
    }

    public static function deleteBatchByPermissionId($permissionId)
    {
        return PermissionAssignModel::deleteAll('permission_id = :pid', [':pid' => $permissionId]);
    }

    public static function deleteByRouteId($routeId)
    {
        return PermissionAssignModel::deleteAll('route_id = :rid', [':rid' => $routeId]);
    }

    public static function getAllByPermissionIdsArray($permissionIdsArray)
    {
        foreach($permissionIdsArray as &$permissionId){
            $permissionId = '\'' . $permissionId . '\'';
        }

        $permissionIdsString = '(' . implode(',', $permissionIdsArray) . ')';

        return self::find()
            ->where('permission_id in ' . $permissionIdsString)
            ->asArray()
            ->all();
    }
}
