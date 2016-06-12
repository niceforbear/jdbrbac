<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 上午11:10
 */

namespace niceforbear\jdbrbac\services;

use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\models\RbacRoleAssign;

class RoleAssignService
{
    public static function batchAdd($roleId, array $permIds)
    {
        foreach ($permIds as $permId) {
            $result = RbacRoleAssign::addOne($roleId, $permId);
            if (!$result) {
                throw new RbacException('Batch add role assign fail in jdbrbac.RoleAssignService: permId = ' . $permId, 1000050031);
            }
        }

        return true;
    }
}
