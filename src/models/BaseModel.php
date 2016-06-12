<?php
/**
 * @author nesfoubaer
 * @date 16/6/7 下午11:19
 */

namespace niceforbear\jdbrbac\models;

use niceforbear\jdbrbac\helpers\RbacConsts;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public static function getDb()
    {
        return RbacConsts::DB_CONNECT_DB;
    }
}
