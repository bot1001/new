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
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '10']);// 创建分页对象

        $ticket = $ticket->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $ticket= Json::encode($ticket);

        return $ticket;
    }

    //查询当日投诉总数
    function actionCount()
    {
        $time = strtotime(date('Y-m-d')); //当日时间戳
        $count = Ticket::find()
            ->select('count(ticket_number) as count, ticket_status as status')
            ->where(['>=', 'create_time', $time])
            ->orderBy('ticket_status')
            ->groupBy('ticket_status')
            ->asArray()
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