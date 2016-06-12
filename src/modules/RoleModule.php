<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 上午10:54
 */

namespace niceforbear\jdbrbac\modules;

use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\helpers\RbacUtils;
use niceforbear\jdbrbac\models\RbacPermission;
use niceforbear\jdbrbac\models\RbacRole;
use niceforbear\jdbrbac\models\RbacRoleAssign;
use niceforbear\jdbrbac\services\CheckService;
use niceforbear\jdbrbac\services\RoleAssignService;

class RoleModule
{
    public static function addOne($roleName, array $permIds)
    {
        CheckService::checkPermIds($permIds);

        $result = RbacRole::addOne($roleName);
        if (!$result) {
            throw new RbacException('Add role fail in jdbrbac.RoleModule', 100005011);
        }

        $role = RbacRole::getOneByRoleName($roleName);

        return RoleAssignService::batchAdd($role['id'], $permIds);
    }

    public static function deleteByRoleId($roleId)
    {
        CheckService::checkRoleId($roleId);

        $result = RbacRole::deleteById($roleId);
        if (!$result) {
            throw new RbacException('Delete role fail in jdbrbac.RoleModule', 100005013);
        }

        $result = RbacRoleAssign::deleteByRoleId($roleId);
        if (!$result) {
            throw new RbacException('Delete role assign fail in jdbrbac.RoleModule', 100005014);
        }

        return true;
    }

    public static function updateOne($roleId, $roleName, array $permIds)
    {
        CheckService::checkRoleId($roleId);
        CheckService::checkPermIds($permIds);

        $result = RbacRole::updateById($roleId, $roleName);
        if (!$result) {
            throw new RbacException('Update role fail in jdbrbac.RoleModule', 100005015);
        }

        $result = RbacRoleAssign::deleteByRoleId($roleId);
        if (!$result) {
            throw new RbacException('Delete role fail in jdbrbac.RoleModule', 100005016);
        }

        return RoleAssignService::batchAdd($roleId, $permIds);
    }

    public static function getBatch($page, $condition)
    {
        CheckService::checkPage($page);
        CheckService::checkCondition($condition);

        $roles = RbacRole::getByBatch($page, $condition);
        $roleIds = [];
        $retData = [];
        foreach ($roles as $role) {
            $roleIds[] = $role['id'];
            $retData[$role['id']] = [
                'role_id' => $role['id'],
                'role_name' => $role['name'],
                'time_create' => $role['time_create'],
                'time_update' => $role['time_update'],
            ];
        }

        $roleAssigns = RbacRoleAssign::getAllByRoleIds($roleIds);
        foreach ($roleAssigns as $roleAssign) {
            $retData[$roleAssign['role_id']]['permission_id'][] = $roleAssign['permission_id'];
        }

        foreach ($retData as $roleId => $data) {
            $permissions = RbacPermission::getAllByPermissionIds($data['permission_id']);
            $retData[$roleId]['permission_name'] = array_column($permissions, 'name');
        }

        return $retData;
    }

    public static function getOneByRoleId($roleId)
    {
        CheckService::checkRoleId($roleId);

        $role = RbacRole::getOneByRoleId($roleId);
        $roleAssigns = RbacRoleAssign::getAllByRoleIds([$roleId]);
        $role['permission_id'] = array_column($roleAssigns, 'permission_id');
        return $role;
    }

    public static function getAllPermission()
    {
        return RbacPermission::getAll();
    }

    public static function isRoleNameOccupied($roleName)
    {
        return RbacRole::isRoleNameOccupied($roleName);
    }
}
