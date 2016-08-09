<?php
/**
 * @author nesfoubaer
 * @date 16/6/30 下午2:11
 */

namespace app\jdbrbac\models;

use app\jdbrbac\components\Utils;

class UserModel extends BaseModel
{
    const COL_ID = '';
    const COL_IDENTITY = '';
    const COL_EMAIL = '';
    const COL_PHONE = '';

    public static function checkId($id)
    {
    }

    public static function getDb()
    {
        return parent::getDb();
    }

    public static function tableName()
    {
        return Utils::TABLE_USER;
    }

    public static function getAll()
    {
        return self::find()
            ->select([self::COL_ID, self::COL_IDENTITY, self::COL_EMAIL, self::COL_PHONE])
            ->asArray()
            ->all();
    }

    public static function getColumns()
    {
        return [
            self::COL_ID,
            self::COL_IDENTITY,
            self::COL_EMAIL,
            self::COL_PHONE
        ];
    }
}
