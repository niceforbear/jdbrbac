<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 上午11:29
 */

namespace niceforbear\jdbrbac\modules;

use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\helpers\RbacUtils;
use niceforbear\jdbrbac\models\RbacPermission;
use niceforbear\jdbrbac\models\RbacPermissionAssign;
use niceforbear\jdbrbac\models\RbacRoute;
use niceforbear\jdbrbac\services\CheckService;
use niceforbear\jdbrbac\services\PermissionAssignService;

class PermissionModule
{
    public static function addOne($permName, $routeIds)
    {
        CheckService::checkRouteIds($routeIds);

        $result = RbacPermission::addOne($permName);
        if (!$result) {
            throw new RbacException('Add one permission fail in PermissionModule', 100005001);
        }

        $permission = RbacPermission::getByPermissionName($permName);
        return PermissionAssignService::batchAdd($permission['id'], $routeIds);
    }

    public static function deleteByPermissionId($permId)
    {
        CheckService::checkPermId($permId);

        $result = RbacPermission::deleteByPermissionId($permId);
        if (!$result) {
            throw new RbacException('Delete one permission fail in PermissionModule', 100005002);
        }

        $result = RbacPermissionAssign::deleteByPermissionId($permId);
        if (!$result) {
            throw new RbacException('Delete permission assign fail in PermissionModule', 100005003);
        }

        return true;
    }

    public static function updateOne($permId, $permName, array $routeIds)
    {
        CheckService::checkPermId($permId);
        CheckService::checkRouteIds($routeIds);

        $result = RbacPermission::updateOne($permId, $permName);
        if (!$result) {
            throw new RbacException('Update one permission fail in PermissionModule', 100005004);
        }

        $result = RbacPermissionAssign::deleteByPermissionId($permId);
        if ($result) {
            throw new RbacException('Delete permission fail in PermissionModule', 100005005);
        }

        return PermissionAssignService::batchAdd($permId, $routeIds);
    }

    public static function getBatch($page, $condition)
    {
        CheckService::checkPage($page);
        CheckService::checkCondition($condition);

        $permissions = RbacPermission::getBatch($page, $condition);
        $permIds = [];
        $retData = [];
        foreach ($permissions as $permission) {
            $permIds[] = $permission['id'];
            $retData[$permission['id']] = $permission;
        }

        $permAssigns = RbacPermissionAssign::getAllByPermIds($permIds);
        $routeIds = [];
        foreach ($permAssigns as $permAssign) {
            $routeIds[] = $permAssign['route_id'];
            $retData[$permAssign['permission_id']]['route_id'][] = $permAssign['route_id'];
        }

        foreach ($retData as $permId => $data) {
            $routes = RbacRoute::getByIds($data['route_id']);
            $retData[$permId]['route_name'] = array_column($routes, 'name');
        }

        return $retData;
    }

    public static function getOneByPermissionId($permId)
    {
        CheckService::checkPermId($permId);

        $permission = RbacPermission::getOneByPermissionId($permId);
        $permAssigns = RbacPermissionAssign::getAllByPermIds([$permId]);
        $permission['route_id'] = array_column($permAssigns, 'route_id');
        return $permission;
    }

    public static function getAllRoute()
    {
        return RbacRoute::getAll();
    }

    public static function isPermissionNameOccupied($permName)
    {
        return RbacPermission::isPermissionNameOccupied($permName);
    }
}
