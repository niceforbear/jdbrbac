<?php
/**
 * @author nesfoubaer
 * @date 16/6/28 下午3:44
 */

namespace app\jdbrbac\models;

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public static function getDb()
    {
        return '';
    }

    public static function baseCheckTableId($id)
    {
    }
}
