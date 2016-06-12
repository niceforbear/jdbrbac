<?php
/**
 * @author nesfoubaer
 * @date 16/6/8 上午11:29
 */

namespace niceforbear\jdbrbac\modules;

use niceforbear\jdbrbac\models\RbacRoute;
use niceforbear\jdbrbac\services\CheckService;

class RouteModule
{
    public static function addOne($routeName, $route, $system)
    {
        CheckService::checkRoute($route);
        return RbacRoute::addOne($routeName, $route, $system);
    }

    public static function deleteByRouteId($routeId)
    {
        CheckService::checkRouteId($routeId);
        return RbacRoute::deleteByRouteId($routeId);
    }

    public static function updateOne($routeId, $routeName, $route, $system)
    {
        CheckService::checkRouteId($routeId);
        CheckService::checkRoute($route);
        return RbacRoute::updateOne($routeId, $routeName, $route, $system);
    }

    public static function getAll()
    {
        return RbacRoute::getAll();
    }

    public static function getBatch($page, $condition)
    {
        return RbacRoute::getBatch($page, $condition);
    }

    public static function getOneByRouteId($routeId)
    {
        return RbacRoute::getOneById($routeId);
    }
}
