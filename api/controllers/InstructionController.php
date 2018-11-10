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
use yii\data\Pagination;

class InstructionController extends Controller
{
    //小程序操作指南标题列表
    function actionIndex($type, $page)
    {
        $instruction = Instructions::find() //获取指南标题数据
            ->select('id, title, version, create_time, update_time')
            ->where(['type' => "$type", 'status' => '1']);

        $count = $instruction->count(); //求总页数
        if($count == '0') //如果数据为空则返回空
        {
            return false;
        }

        $p = '10';
        $pa = ceil($count/$p);
        if($page > $pa)
        {
            return false;
        }

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $p]);
        $instruction = $instruction->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $instruction = Json::encode($instruction);//数据转换

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

        if($instruction){ //判断是否存在数据
            //数据转换
            $instruction = Json::encode($instruction);
            return $instruction;
        }
        return false;
    }

    function actionWeb($id)
    {
        $instruction = (new Query())
            ->select('instructions.id, instructions.title, instructions.content, instructions.author, from_unixtime(instructions.create_time) as create_time,
            from_unixtime(instructions.update_time) as update_time, instructions.version, instructions.property, sys_user.name')
            ->from('instructions')
            ->join('inner join', 'sys_user', 'sys_user.id = instructions.author')
            ->where(['instructions.id' => $id])
            ->orderBy('sort DESC, update_time DESC')
            ->one();

        if($instruction){
            return $this->render('index',['model' => $instruction]);
        }

        return false;
    }

    //通过指南类型查找
    function actionAbout($id){
        $instruction = (new Query())
            ->select('instructions.id, instructions.title, instructions.content, instructions.author, from_unixtime(instructions.create_time) as create_time,
            from_unixtime(instructions.update_time) as update_time, instructions.version, instructions.property, sys_user.name')
            ->from('instructions')
            ->join('inner join', 'sys_user', 'sys_user.id = instructions.author')
            ->where(['instructions.type' => "$id"])
            ->orderBy('sort DESC, update_time DESC')
            ->one();

        if($instruction){
            return $this->render('index',['model' => $instruction]);
        }

        return false;
    }
}