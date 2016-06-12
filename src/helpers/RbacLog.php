<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午2:10
 */

namespace niceforbear\jdbrbac\helpers;

use Yii;

class RbacLog
{
    public static function exception(RbacException $e)
    {
        $eString = $e->getFile() . ', ' .
            $e->getName() . ', ' .
            $e->getLine() . ', ' .
            $e->getMessage() . ', ' .
            $e->getCode();
        Yii::error($eString, RbacConsts::LOG_EXCEPTION);
    }
}
