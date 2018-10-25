<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/25
 * Time: 8:23
 */

namespace api\controllers;


use common\models\Instructions;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class InstructionController extends Controller
{
    //小程序操作指南标题列表
    function actionIndex($type)
    {
        $instruction = Instructions::find() //获取指南标题数据
            ->select('id, title')
            ->where(['type' => "$type", 'status' => '1'])
            ->asArray()
            ->all();
        //数据转换
        $instruction = Json::encode($instruction);

        return $instruction;
    }

    //获取单条指南数据
    function actionOne($id)
    {
        $instruction = (new Query())
            ->select('instructions.id, instructions.title, instructions.content, instructions.author, from_unixtime(instructions.create_time) as create_time,
            from_unixtime(instructions.update_time) as update_time, instructions.version, instructions.property, sys_user.name')
            ->from('instructions')
            ->join('inner join', 'sys_user', 'sys_user.id = instructions.author')
            ->where(['instructions.id' => $id])
            ->one();

        //数据转换
        $instruction = Json::encode($instruction);

        return $instruction;
    }
}