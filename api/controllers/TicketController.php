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

    //支付助手查询单个投诉
    function actionOne($ticket_id)
    {
        $ticket = Ticket::find()
            ->select('explain1 as explain, assignee_id as account_id')
        ->where(['ticket_id' => "$ticket_id"])
        ->asArray()
        ->one();

        $user = (new \yii\db\Query()) //查询回经手人信息
            ->select('user_data.real_name as staff, user_account.mobile_phone')
            ->from('user_account')
            ->join('inner join', 'user_data', 'user_data.account_id = user_account.account_id')
            ->where(['user_account.account_id' => $ticket['account_id']])
            ->one();

        if(!$user){ //如果 $user 为空则赋以空值
            $user = ['staff' => '', 'mobile_phone' => ''];
        }

        $answer = (new \yii\db\Query()) //查询回复人信息及内容
            ->select('user_data.real_name as answer_name, ticket_reply.content, user_account.account_role as role, ticket_reply.reply_time')
            ->from('ticket_reply')
            ->join('inner join', 'user_data', 'user_data.account_id = ticket_reply.account_id')
            ->join('inner join', 'user_account', 'user_account.account_id = ticket_reply.account_id')
            ->where(['ticket_reply.ticket_id' => "$ticket_id"])
            ->orderBy('ticket_reply.reply_time')
            ->all();

        $result = ['ticket' => $ticket, 'user' => $user, 'answer' => $answer];

        $result = Json::encode($result);
        return $result;
    }

    //支付助手
    function actionList($fromdate, $todate, $community, $page, $status)
    {
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }

        $community = Json::decode($community); //数组转换

        $ticket = (new \yii\db\Query())
            ->select(["ticket_basic.ticket_id, concat( community_basic.community_name,' ', community_building.building_name,' ',community_realestate.room_number, ' ',community_realestate.room_name) as address
            ,ticket_basic.contact_phone as phone, ticket_basic.contact_person as name, from_unixtime(ticket_basic.create_time) as time, ticket_basic.ticket_status as status"])
            ->from('ticket_basic')
            ->join('inner join', 'community_realestate', 'community_realestate.realestate_id = ticket_basic.realestate_id')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->andwhere(['between', 'ticket_basic.create_time', strtotime($fromdate),strtotime($todate)])
            ->andWhere(['in', 'community_basic.community_name', $community])
            ->andFilterWhere(['ticket_basic.ticket_status' => $status])
            ->orderBy('ticket_basic.create_time DESC');

        $count = $ticket->count(); //求总数
        $p = '10';

        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => "$p"]); //实例化分页模型并设置每页获取数量

        $ticket = $ticket->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $ticket = Json::encode($ticket); //数组缓缓
        return $ticket;
    }

    //支付助手查询当日投诉总数
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
            ->join('inner join', 'community_basic', 'community_basic.community_id = ticket_basic.community_id')
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