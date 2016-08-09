<?php
/**
 * @author nesfoubaer
 * @date 16/6/28 下午4:53
 */

namespace app\jdbrbac\components;

use Yii;
use yii\helpers\Html;

class Utils
{
    /** Notice: This constants could be: dev, prod. */
    const ENVIRONMENT = 'dev';

    public static $common = [];

    public static $config = [
        'dev' => [
            'source_data' => [
                [
                    'dir' => '/data/www/dmp/controllers',
                    'namespace' => '\app\\controllers\\',
                    'prefix' => '',
                ],
                [
                    'dir' => '/data/www/dmp/modules/Cms/controllers',
                    'namespace' => '\app\\modules\\Cms\\controllers\\',
                    'prefix' => '/cms',
                ]
            ],
        ],
        'prod' => [
            'source_data' => [],
        ],
    ];

    /** Table names */
    const TABLE_ROUTE = 'jdb_rbac_route';
    const TABLE_ROLE = 'jdb_rbac_role';
    const TABLE_ROLE_ASSIGN = 'jdb_rbac_role_assign';
    const TABLE_PERMISSION = 'jdb_rbac_permission';
    const TABLE_PERMISSION_ASSIGN = 'jdb_rbac_permission_assign';
    const TABLE_USER_ASSIGN = 'jdb_rbac_user_assign';
    const TABLE_USER = 'user';
    /** Table names */

    public static function getNow()
    {
        return date('Y-m-d H:i:s', time());
    }

    /**
     * 异常封装处理
     */
    public static function handlerForException(JdbRbacException $e)
    {
        return self::responseError([], $e->getMessage(), '500' . strval($e->getCode()));
    }

    /**
     * bool型结果封装处理
     */
    public static function handlerForResult($result)
    {
        if ($result) {
            return self::responseOK();
        } else {
            return self::responseError();
        }
    }

    public static function responseOK($data = [], $errmsg = 'Success', $errno = 0)
    {
        return self::baseResponse($data, $errmsg, $errno);
    }

    public static function responseError($data = [], $errmsg = 'Error', $errno = 1)
    {
        return self::baseResponse($data, $errmsg, $errno);
    }

    private static function baseResponse($data, $errmsg, $errno)
    {
        $template = [
            'errmsg' => $errmsg,
            'errno' => $errno,
            'data' => $data
        ];

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $template;
    }

    public static function filterRequest()
    {
        foreach (Yii::$app->request->post() as $k => $v) {
            if (is_array($v) || is_resource($v) || is_uploaded_file($v)) {
                continue;
            }
            Yii::$app->request->post()[$k] = trim(Html::encode($v));
        }

        foreach (Yii::$app->request->get() as $k => $v) {
            if (is_array($v)) {
                continue;
            }
            Yii::$app->request->get()[$k] = trim(Html::encode($v));
        }
    }

    public static function dump($msg)
    {
        echo $msg . "<br />" . PHP_EOL;
    }
}
