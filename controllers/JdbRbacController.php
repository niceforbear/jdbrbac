<?php
/**
 * @author nesfoubaer
 * @date 16/6/29 下午3:09
 */

namespace app\jdbrbac\controllers;

use app\jdbrbac\components\UpdateRoute;
use Yii;
use app\jdbrbac\models\UserModel;
use app\jdbrbac\models\RoleModel;
use app\jdbrbac\models\RouteModel;
use app\jdbrbac\models\UserAssignModel;
use app\jdbrbac\models\PermissionModel;
use app\jdbrbac\models\RoleAssignModel;
use app\jdbrbac\models\PermissionAssignModel;
use app\jdbrbac\components\Utils;
use app\jdbrbac\components\JdbRbacException;

class JdbRbacController extends \yii\web\Controller
{
    public function actionTest()
    {
        echo time();
        exit;
    }

    public function beforeAction($action)
    {
        Utils::filterRequest();
        return parent::beforeAction($action);
    }

    /**
     * 更新系统路由
     */
    public function actionUpdateSystemRoute()
    {
        try {
            $sourceData = Utils::$config[Utils::ENVIRONMENT]['source_data'];
            foreach ($sourceData as $sourceDatum) {
                UpdateRoute::checkSourceData($sourceDatum);

                $files = scandir($sourceDatum['dir']);
                foreach ($files as $file) {
                    $routes = UpdateRoute::scanFile($file, $sourceDatum['namespace'], $sourceDatum['prefix']);
                    UpdateRoute::batchAddRoute($routes);
                }
            }

            return Utils::responseOK([]);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得全局路由: 包括系统 + 自定义
     */
    public function actionGetAllRoute()
    {
        try {
            $data = RouteModel::getAllRoute();
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得系统路由
     */
    public function actionGetSystemRoute()
    {
        try {
            $data = RouteModel::getSystemRoute();
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得所有自定义路由
     */
    public function actionGetCustomRoute()
    {
        try {
            $data = RouteModel::getCustomRoute();
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 添加自定义路由
     */
    public function actionAddOneCustomRoute()
    {
        try {
            $route = Yii::$app->request->post(RouteModel::COL_ROUTE);
            $method = Yii::$app->request->post(RouteModel::COL_EXT_METHOD);
            $params = Yii::$app->request->post(RouteModel::COL_EXT_PARAMS);
            RouteModel::checkRoute($route);
            RouteModel::checkParams($method, $params);

            $result = RouteModel::addOneCustomRoute($route, $method, $params);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得自定义路由
     */
    public function actionGetOneCustomRoute()
    {
        try {
            $id = Yii::$app->request->get(RouteModel::COL_ID);
            RouteModel::checkId($id);
            $data = RouteModel::getOneCustomRoute($id);
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 更新自定义路由
     */
    public function actionUpdateOneCustomRoute()
    {
        try {
            $id = Yii::$app->request->post(RouteModel::COL_ID);
            $route = Yii::$app->request->post(RouteModel::COL_ROUTE);
            $method = Yii::$app->request->post(RouteModel::COL_EXT_METHOD);
            $params = Yii::$app->request->post(RouteModel::COL_EXT_PARAMS);
            RouteModel::checkId($id);
            RouteModel::checkRoute($route);
            RouteModel::checkParams($method, $params);

            $result = RouteModel::updateOneCustomRoute($id, $route, $method, $params);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 删除一个自定义路由 & 所有该路由的权限分配
     */
    public function actionDeleteOneCustomRoute()
    {
        try {
            $id = Yii::$app->request->post(RouteModel::COL_ID);
            RouteModel::checkId($id);

            $result = PermissionModel::deleteByRouteId($id);

            if (!$result) {
                throw new JdbRbacException('Delete permission assign fail', 50015680);
            }

            $result = RouteModel::deleteOneCustomRoute($id);
            if (!$result) {
                throw new JdbRbacException();
            }

            $result = PermissionAssignModel::deleteByRouteId($id);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得权限列表
     */
    public function actionGetPermission()
    {
        try {
            $data = PermissionModel::getPermission();
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 添加一个权限
     *
     * @params string route_ids '11,22,33,44,55'
     */
    public function actionAddOnePermission()
    {
        try {
            $name = Yii::$app->request->post(PermissionModel::COL_NAME);
            $routeIds = Yii::$app->request->post('route_ids');
            PermissionModel::checkName($name);
            PermissionAssignModel::checkRouteIds($routeIds);
            $result = PermissionModel::addOnePermission($name);
            if (!$result) {
                throw new JdbRbacException();
            }

            $id = PermissionModel::getOnePermissionIdByName($name);
            $result = PermissionAssignModel::addBatch($id, $routeIds);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得一个权限数据
     */
    public function actionGetOnePermission()
    {
        try {
            $id = Yii::$app->request->get(PermissionModel::COL_ID);
            PermissionModel::checkId($id);

            $data = [
                'permission' => PermissionModel::getOnePermission($id),
                'permission_assign' => PermissionAssignModel::getAllByPermissionId($id)
            ];
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 更新一个权限
     */
    public function actionUpdateOnePermission()
    {
        try {
            $id = Yii::$app->request->post(PermissionModel::COL_ID);
            $name = Yii::$app->request->post(PermissionModel::COL_NAME);
            $routeIds= Yii::$app->request->post('route_ids');
            PermissionModel::checkId($id);
            PermissionModel::checkName($name);
            PermissionAssignModel::checkRouteIds($routeIds);

            $result = PermissionModel::updateOnePermission($id, $name);
            if (!$result) {
                throw new JdbRbacException();
            }

            $result = PermissionAssignModel::deleteBatchByPermissionId($id);
            if (!$result) {
                throw new JdbRbacException();
            }

            $result = PermissionAssignModel::addBatch($id, $routeIds);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 删除一个权限 & 所有该权限的角色分配
     */
    public function actionDeleteOnePermission()
    {
        try {
            $id = Yii::$app->request->post(PermissionModel::COL_ID);
            PermissionModel::checkId($id);

            $result = PermissionModel::deleteOnePermission($id);
            if (!$result) {
                throw new JdbRbacException();
            }

            $result = PermissionAssignModel::deleteBatchByPermissionId($id);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得角色列表
     */
    public function actionGetRole()
    {
        try {
            $data = RoleModel::getRole();
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 添加一个角色
     */
    public function actionAddOneRole()
    {
        try {
            $name = Yii::$app->request->post(RoleModel::COL_NAME);
            $permissionIds = Yii::$app->request->post('permission_ids');
            RoleModel::checkName($name);
            RoleAssignModel::checkPermissionIds($permissionIds);

            $result = RoleModel::addOne($name);
            if (!$result) {
                throw new JdbRbacException();
            }

            $roleId = RoleModel::getOneIdByName($name);
            $result = RoleAssignModel::addBatch($roleId, $permissionIds);

            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得一个角色数据
     */
    public function actionGetOneRole()
    {
        try {
            $id = Yii::$app->request->get(RoleModel::COL_ID);
            RoleModel::checkId($id);

            $data = [
                'role' => RoleModel::getOneById($id),
                'role_assign' => RoleAssignModel::getAllByRoleId($id)
            ];

            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 更新一个角色
     */
    public function actionUpdateOneRole()
    {
        try {
            $roleId = Yii::$app->request->post(RoleModel::COL_ID);
            $name = Yii::$app->request->post(RoleModel::COL_NAME);
            $permissionIds = Yii::$app->request->post('permission_ids');
            RoleModel::checkId($roleId);
            RoleModel::checkName($name);
            RoleAssignModel::checkPermissionIds($permissionIds);

            $result = RoleModel::updateOneRole($roleId, $name);
            if (!$result) {
            }

            $result = RoleAssignModel::deleteAllByRoleId($roleId);
            if (!$result) {
            }

            $result = RoleAssignModel::addBatch($roleId, $permissionIds);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 删除一个角色 & 所有该角色的用户分配
     */
    public function actionDeleteOneRole()
    {
        try {
            $roleId = Yii::$app->request->post(RoleModel::COL_ID);
            RoleModel::checkId($roleId);

            $result = RoleAssignModel::deleteAllByRoleId($roleId);
            if (!$result) {
            }

            $result = RoleModel::deleteOne($roleId);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得用户分配列表
     */
    public function actionGetUserAssign()
    {
        try {
            $data = UserAssignModel::getUserAssign();
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 添加一个用户分配
     */
    public function actionAddOneUserAssign()
    {
        try {
            $userId = Yii::$app->request->post(UserAssignModel::COL_USER_ID);
            $roleIds = Yii::$app->request->post('role_ids');
            UserAssignModel::checkUserId($userId);
            UserAssignModel::checkRoleIds($roleIds);

            $result = UserAssignModel::addBatch($userId, $roleIds);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得一个用户分配的具体信息
     */
    public function actionGetOneUserAssign()
    {
        try {
            $userId = Yii::$app->request->post(UserAssignModel::COL_USER_ID);
            UserAssignModel::checkUserId($userId);

            $data = UserAssignModel::getAllByUserId($userId);
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 更新一个用户分配
     */
    public function actionUpdateOneUserAssign()
    {
        try {
            $userId = Yii::$app->request->post(UserAssignModel::COL_USER_ID);
            $roleIds = Yii::$app->request->post('role_ids');
            UserAssignModel::checkUserId($userId);
            UserAssignModel::checkRoleIds($roleIds);

            $result = UserAssignModel::deleteAllByUserId($userId);
            if (!$result) {
            }

            $result = UserAssignModel::addBatch($userId, $roleIds);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 删除一个用户分配
     */
    public function actionDeleteOneUserAssign()
    {
        try {
            $userId = Yii::$app->request->post(UserAssignModel::COL_USER_ID);
            UserAssignModel::checkUserId($userId);

            $result = UserAssignModel::deleteAllByUserId($userId);
            return Utils::handlerForResult($result);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }

    /**
     * 获得用户列表
     */
    public function actionGetUser()
    {
        try {
            $data = [
                'list' => UserModel::getAll(),
                'columns' => UserModel::getColumns()
            ];
            return Utils::responseOK($data);
        } catch (JdbRbacException $e) {
            return Utils::handlerForException($e);
        }
    }
}
