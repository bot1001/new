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
    function actionIndex()
    {
        $get = $_GET;

        if(isset($get['account_id'])) //判断数据中是否存在投诉编号
        {
            $account_id = $get['account_id'];
        }else{
            return false;
        }

        $ticket = Ticket::find()->where(['account_id' => "$account_id"]);

        $count = $ticket->count(); // 计算总数
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '1']);// 创建分页对象

        $ticket = $ticket->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        //判断获取的页数是否为最大
        if(isset($get['page']))
        {
            $page = $get['page'];
            if($get['page'] > $count){
                return false;
            }
        }

        $ticket= Json::encode($ticket);

        return $ticket;
    }
}