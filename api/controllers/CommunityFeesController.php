<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/25
 * Time: 8:23
 */

namespace api\controllers;

use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class CommunityFeesController extends Controller
{
    //小程序收费标准标题列表
    function actionIndex($community)
    {
        $fees = (new \yii\db\Query()) //获取指南标题数据
            ->select('community_fees.id, community_fees.title')
            ->from('community_fees')
            ->join('inner join', 'community_basic', 'community_fees.community_id = community_basic.community_id')
            ->where(['community_basic.community_name' => "$community", 'community_fees.status' => '1'])
            ->all();

        //数据转换
        $fees = Json::encode($fees);

        return $fees;
    }

    //获取单条收费标准数据
    function actionOne($id)
    {
        $fees = (new Query())
            ->select('community_fees.id, community_fees.title, community_fees.content, community_fees.author, from_unixtime(community_fees.create_time) as create_time,
            from_unixtime(community_fees.update_time) as update_time, community_fees.version, community_fees.property, sys_user.name')
            ->from('community_fees')
            ->join('inner join', 'sys_user', 'sys_user.id = community_fees.author')
            ->where(['community_fees.id' => $id])
            ->one();

        //数据转换
        $fees = Json::encode($fees);

        return $fees;
    }
}