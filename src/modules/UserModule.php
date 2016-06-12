<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 上午11:28
 */

namespace niceforbear\jdbrbac\modules;

use niceforbear\jdbrbac\helpers\RbacConsts;
use niceforbear\jdbrbac\helpers\RbacUtils;
use niceforbear\jdbrbac\models\RbacRole;
use niceforbear\jdbrbac\models\RbacUserAssign;
use niceforbear\jdbrbac\services\CheckService;
use niceforbear\jdbrbac\services\UserAssignService;

class UserModule
{
    public static function addAssign($userId, array $roleIds)
    {
        CheckService::checkUserId($userId);
        CheckService::checkRoleIds($roleIds);

        return UserAssignService::batchAdd($userId, $roleIds);
    }

    public static function deleteAssignByUserId($userId)
    {
        CheckService::checkUserId($userId);

        return RbacUserAssign::deleteByRoleId($userId);
    }

    public static function updateAssign($userId, array $roleIds)
    {
        CheckService::checkUserId($userId);
        CheckService::checkRoleIds($roleIds);

        RbacUserAssign::deleteByUserId($userId);

        return UserAssignService::batchAdd($userId, $roleIds);
    }

    public static function getRoleByUserIds(array $userIds)
    {
        CheckService::checkUserIds($userIds);

        $retData = [];
        foreach ($userIds as $userId) {
            $userAssigns = RbacUserAssign::getAllByUserId($userId);
            $roleIds = [];
            foreach ($userAssigns as $userAssign) {
                $roleIds[] = $userAssign['role_id'];
            }

            $roles = RbacRole::getAllByRoleIds($roleIds);

            $retData[$userId] = [
                RbacConsts::REQUEST_USER_ID => $userId,
                'roles' => $roles
            ];
        }

        return $retData;
    }

    public static function getAllRole()
    {
        return RbacRole::getAll();
    }
}
