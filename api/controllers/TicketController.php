<?php
namespace api\controllers;

use common\models\Ticket;
use yii\web\Controller;
use yii\helpers\Json;
use yii\data\Pagination;

/**
 * Site controller
 */
class TicketController extends Controller
{
    //批量查询
    function actionIndex($account_id, $page)
    {
        $ticket = Ticket::find()->where(['account_id' => "$account_id"]);

        $count = $ticket->count(); // 计算总数

        $p = '10';
        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $p]);// 创建分页对象

        $ticket = $ticket->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $ticket= Json::encode($ticket);

        return $ticket;
    }

    //查询当日投诉总数
    function actionCount($fromdate, $todate, $community)
    {
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }

        $community = Json::decode($community); //将json数组转换为普通数组
        $count = (new \yii\db\Query())
            ->select('count(ticket_number) as count, ticket_status as status')
            ->from('ticket_basic')
            ->join('inner join', 'community_Basic', 'community_basic.community_id = ticket_basic.community_id')
            ->where(['between', 'ticket_basic.create_time', strtotime($fromdate), strtotime($todate)])
            ->andWhere(['or like', 'community_basic.community_name', $community])
            ->orderBy('ticket_status')
            ->groupBy('ticket_status')
            ->all();

        $sum = 0; //设置未处理投诉量为0
        foreach($count as $c) //遍历求未处理数量
        {
            if($c['status'] == '1'){
                $sum = $c['count'];
            }
        }

        $_count = array_column($count, 'count'); //提取投诉量总数
        $_count = array_sum($_count); //投诉量求和
        $count = ['count' => $_count, '_count' => $sum]; //重组返回数据
        $count = Json::encode($count);

        return $count;
    }
}