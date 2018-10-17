<?php
namespace api\controllers;

use common\models\Advertising;
use common\models\Community;
use yii\helpers\Json;
use yii\web\Controller;

class AdvertiseController extends Controller
{
    //小程序广告
    function actionIndex($community)
    {
        $comm = Community::find() //查询小区编码
            ->where(['community_id' => "$community"])
            ->asArray()
            ->one();

        $advertise = Advertising::find() //获取广告数据
            ->select('ad_id as id, ad_poster as poster')
            ->andFilterWhere(['ad_publish_community' => "$comm"])
            ->andWhere(['ad_status' => '1'])
            ->orderBy('ad_sort ASC, ad_end_time DESC')
            ->limit('5')
            ->asArray()
            ->all();

        $url = 'http://img.gxydwy.com/';

        //遍历缩略图并重新组合图片路径
        foreach ($advertise as $ad){
            $u = $url.$ad['poster'];
            $ad['poster'] = $u;
            $a[] = $ad;
        }

        return Json::encode($a);
    }
}