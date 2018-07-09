<?php

namespace app\models;

use mdm\admin\components\Helper;

class Limit extends \yii\db\ActiveRecord
{
    static function limit($rul)
    {
        //判断登陆用户是否由修改权限
        if(Helper::checkRoute($rul))
        {
            $limit = '1';
        }else{
            $limit = '0';
        }

        return $limit;
    }
}
