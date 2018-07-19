<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\TicketReply */

$this->title = $model->reply_id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Replies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-reply-view" style="max-width: 800px">

    <p align="right">
        <?php		
		if (Helper::checkRoute('delete')) {
            echo Html::a('删除', ['delete', 'id' => $model->reply_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '您确定要删除么?',
                        'method' => 'post',
                    ],
                ]);
        } ?>
    </p>
   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
		   ['attribute' => 'a.user_name',
		   'label' => '姓名'
		   ],
		
           ['attribute' => 'content',
		   'label' => '详情'
		   ],
		
           ['attribute' => 'time',
			'value'=>date('Y-m-d H:i:s',$model->reply_time),
		   'label' => '时间'
		   ],
        ],
    ]) ?>
</div>
