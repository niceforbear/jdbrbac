<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午2:00
 */

namespace niceforbear\jdbrbac\controllers;

use Yii;

class RbacController extends BaseController
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
}
