<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午2:05
 */

namespace niceforbear\jdbrbac\helpers;

use Yii;
use yii\helpers\Html;
use niceforbear\jdbrbac\controllers\BaseController;

class RbacUtils
{
    /** @var array Ignored data */
    private static $except = [];

    public static function renderForResult($result, $errMsg = 'Fail')
    {
        return $result == true ? BaseController::responseOk() : BaseController::responseError([], $errMsg);
    }

    public static function renderForException(RbacException $e)
    {
        RbacLog::exception($e);
        return BaseController::responseError([], $e->getMessage(), $e->getCode());
    }

    public static function filterRequest()
    {
        foreach (Yii::$app->request->post() as $k => $v) {
            if (in_array($k, self::$except)) {
                continue;
            }
            Yii::$app->request->post()[$k] = Html::encode($v);
        }

        foreach (Yii::$app->request->get() as $k => $v) {
            if (in_array($k, self::$except)) {
                continue;
            }
            Yii::$app->request->get()[$k] = Html::encode($v);
        }
    }

    public static function checkInt($int, $responseBool = false, $isCheckPositive = true)
    {
        if (!is_numeric($int) || ($isCheckPositive == true && $int < 0)) {
            if ($responseBool == false) {
                throw new RbacException('Invalid user id in jdbrbac.RbacUtils', 1000040342);
            } else {
                return false;
            }
        }

        return true;
    }

    public static function getDate($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    /**
     * 系统内部其他地方使用
     */
    public static function checkAndGetData($result)
    {
        $decode = json_decode($result, true);
        if (RbacConsts::IS_RESPONSE_CAPSULE) {
            if (isset($decode['error']['returnCode']) &&
                $decode['error']['returnCode'] == RbacConsts::INFO_OK) {
                return $decode['data'];
            }
        } else {
            if (isset($decode['errno']) && $decode['errno'] == RbacConsts::INFO_OK) {
                return $decode['data'];
            }
        }

        return false;
    }
}
