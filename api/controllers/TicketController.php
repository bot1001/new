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
}