<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 上午11:31
 */

namespace niceforbear\jdbrbac\services;

use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\models\RbacPermissionAssign;

class PermissionAssignService
{
    public static function batchAdd($permId, array $routeIds)
    {
        foreach ($routeIds as $routeId) {
            $result = RbacPermissionAssign::addOne($permId, $routeId);
            if (!$result) {
                throw new RbacException('Batch add permission assign fail in jdbrbac.PermissionAssignService', 1000050021);
            }
        }

        return true;
    }
}
