<?php
/**
 * @author 
 * @date 16/6/29 下午3:06
 */

namespace app\jdbrbac;

use Yii;
use app\jdbrbac\components\JdbRbacException;
use app\jdbrbac\components\Utils;
use app\jdbrbac\models\PermissionAssignModel;
use app\jdbrbac\models\RoleAssignModel;
use app\jdbrbac\models\RouteModel;
use app\jdbrbac\models\UserAssignModel;
use yii\base\Module;

class JdbRbac extends Module
{
    public $controllerNamespace = 'app\jdbrbac\controllers';

    public function init()
    {
        parent::init();
    }

    /**
     * 系统内部检测user_id是否可以请求当前资源节点
     *
     * @param mixed $userId 用户ID
     * @return string
     */
    public static function isAllowed($userId, $systemId = 0)
    {
        try {
            $userAssigns = UserAssignModel::getAllByUserId($userId);
            $roleIdsArray = array_column($userAssigns, UserAssignModel::COL_ROLE_ID);
            $roleAssigns = RoleAssignModel::getAllByRoleIdsArray($roleIdsArray);
            $permissionIdsArray = array_column($roleAssigns, RoleAssignModel::COL_PERMISSION_ID);
            $permissionAssigns = PermissionAssignModel::getAllByPermissionIdsArray($permissionIdsArray);
            $routeIdsArray = array_column($permissionAssigns, PermissionAssignModel::COL_ROUTE_ID);
            $rawRoutes = RouteModel::getAllByIdsArray($routeIdsArray);
            $routes = array_column($rawRoutes, RouteModel::COL_ROUTE);

            $currentRoute = self::getRequestRoute();
            if(in_array($currentRoute, $routes)){
                return true;
            }else{
                return false;
            }
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    private static function getRequestRoute()
    {
        return Yii::$app->request->pathInfo;
    }
}
