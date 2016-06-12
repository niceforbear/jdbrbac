<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 下午2:04
 */

namespace niceforbear\jdbrbac\modules;

use niceforbear\jdbrbac\models\RbacRoute;
use Yii;
use niceforbear\jdbrbac\models\RbacUserAssign;
use niceforbear\jdbrbac\helpers\RbacException;
use niceforbear\jdbrbac\models\RbacPermissionAssign;
use niceforbear\jdbrbac\models\RbacRoleAssign;
use niceforbear\jdbrbac\services\CheckService;

class CheckModule
{
    public static function isAllow($userId)
    {
        CheckService::checkUserId($userId);

        $routes = self::getRouteByUserId($userId);
        if (in_array(self::getRequestPathInfo(), $routes)) {
            return true;
        } else {
            return false;
        }
    }

    private static function getRequestPathInfo()
    {
        return Yii::$app->request->pathInfo;
    }

    private static function getRouteByUserId($userId)
    {
        $userAssigns = RbacUserAssign::getAllByUserId($userId);
        $roleIds = array_column($userAssigns, 'role_id');
        if (empty($roleIds) || !is_array($roleIds)) {
            throw new RbacException('Invalid role ids in jdbrbac.CheckModule', 100004031);
        }

        $roleAssigns = RbacRoleAssign::getAllByRoleIds($roleIds);
        $permIds = array_column($roleAssigns, 'permission_id');
        if (empty($permIds) || !is_array($permIds)) {
            throw new RbacException('Invalid perm ids in jdbrbac.CheckModule', 100004032);
        }

        $permAssigns = RbacPermissionAssign::getAllByPermIds($permIds);
        $routeIds = array_column($permAssigns, 'route_id');
        if (empty($routeIds) || !is_array($routeIds)) {
            throw new RbacException('Invalid route ids in jdbrbac.CheckModule', 1000040352);
        }

        $routes = RbacRoute::getByIds($routeIds);
        return array_column($routes, 'route');
    }
}
