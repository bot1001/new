<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/11/8
 * Time: 16:59
 */

namespace api\controllers;


use yii\web\Controller;

class Md5Controller extends Controller
{
    function actionMd5($value, $type){
        $md5 = md5($value);
        if($type == 1){
            $md5 = strtoupper($md5);
        }
        return $md5;
    }
}