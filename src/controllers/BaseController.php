<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午1:46
 */

namespace niceforbear\jdbrbac\controllers;

use Yii;
use yii\web\Controller;
use niceforbear\jdbrbac\helpers\RbacUtils;
use niceforbear\jdbrbac\helpers\RbacConsts;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (RbacConsts::IS_FILTER_DATA) {
            RbacUtils::filterRequest();
        }
        return true;
    }

    public static function responseOk($data = [], $errmsg = 'Ok', $errno = RbacConsts::INFO_OK)
    {
        return self::output($data, $errno, $errmsg);
    }

    public static function responseError($data = [], $errmsg = 'Fail', $errno = RbacConsts::INFO_ERROR)
    {
        return self::output($data, $errno, $errmsg);
    }

    public static function isResponseSuccess($response)
    {
        if (!is_array($response)) {
            $decode = json_decode($response);
        } else {
            $decode = $response;
        }

        if (RbacConsts::IS_RESPONSE_CAPSULE) {
            if ($decode['error']['returnCode'] == RbacConsts::INFO_OK) {
                return true;
            }
        } else {
            if ($decode['errno'] == RbacConsts::INFO_OK) {
                return true;
            }
        }

        return false;
    }

    public static function output($data, $errno, $errmsg)
    {
        if (RbacConsts::IS_RESPONSE_CAPSULE) {
            $response = [
                'error' => [
                    'returnCode' => $errno,
                    'returnMessage' => $errmsg,
                    'returnUserMessage' => $errmsg,
                ],
                'data' => $data
            ];
        } else {
            $response = [
                'errno' => $errno,
                'errmsg' => $errmsg,
                'data' => $data,
            ];
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
}
