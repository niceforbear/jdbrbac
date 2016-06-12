<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 上午10:52
 */

namespace niceforbear\jdbrbac\services;

use niceforbear\jdbrbac\models\RbacUserAssign;
use niceforbear\jdbrbac\helpers\RbacException;

class UserAssignService
{
    public static function batchAdd($userId, $roleIds)
    {
        foreach ($roleIds as $roleId) {
            $result = RbacUserAssign::addOne($userId, $roleId);
            if (!$result) {
                throw new RbacException('Batch add user assign fail in jdbrbac.UserAssignService', 1000050041);
            }
        }

        return true;
    }
}
