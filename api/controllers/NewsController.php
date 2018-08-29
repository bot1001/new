<?php
namespace api\controllers;

use common\models\News;
use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class NewsController extends Controller
{
    //裕家人小程序获取公告列表
    function actionList($community, $page)
    {
        $news = (new \yii\db\Query())
            ->select('community_news.news_id as id, community_basic.community_name as community, community_news.title, community_news.excerpt, community_news.content, from_unixtime(community_news.update_time) as time, community_news.status')
            ->from('community_news')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_news.community_id')
            ->andFilterWhere(['!=', 'status' ,'3'])
            ->andFilterWhere(['community_basic.community_name' => "$community"])
            ->orderBy('update_time DESC')
            ->distinct('community_news.title, community_news.excerpt, community_news.content');

        $count = $news->count(); //求总数

        $p = '10';
        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '10']); //实例化分页并设置每页显示数量

        $news = $news->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $news = Json::encode($news);//转换Json数据
        if(empty($news)){
            return false;
        }else{
            return $news;
        }
    }

    //裕家人小程序单个订单查询
    function actionOne($id)
    {
        $news = (new \yii\db\Query())
            ->select('community_news.news_id as id, community_basic.community_name as community, community_news.title, community_news.excerpt, community_news.content, from_unixtime(community_news.update_time) as time, community_news.status')
            ->from('community_news')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_news.community_id')
            ->Where(['news_id' => "$id"])
            ->orderBy('update_time DESC')
            ->distinct('community_news.title, community_news.excerpt, community_news.content')
            ->one();

        $news = Json::encode($news);//转换Json数据
        if(empty($news)){
            return false;
        }else{
            return $news;
        }
    }

    //裕家人小程序首页公告栏
    function actionHome($community)
    {
        $news = (new \yii\db\Query())
            ->select('community_news.news_id as id, community_basic.community_name as community, community_news.title, community_news.excerpt, community_news.content, from_unixtime(community_news.update_time) as time, community_news.status')
            ->from('community_news')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_news.community_id')
            ->andFilterWhere(['community_basic.community_name' => "$community"])
            ->orderBy('update_time DESC')
            ->distinct('community_news.title, community_news.excerpt, community_news.content')
            ->one();

        $news = Json::encode($news);//转换Json数据
        if(empty($news)){
            return false;
        }else{
            return $news;
        }
    }
}
