<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use app\models\user;

$this->title = '用户登录';

?>

<style type="text/css">
	div{
		margin: auto
	}
   #table_wrap > table {
            font-size: 15px; /*字体*/
            text-align: center;
            /*margin: 0;*/
            border-collapse: separate;
            border-spacing: 0;
            border: 2px #000;
        }

        table thead tr,table tbody tr {
            height: 90px;
            line-height: 10px;
            /*background-color: #eaeaea;*/
        }
        table tr th:first-child,table tr td:first-child {/*设置table左边边框*/
            border-left: 1px solid #eaeaea;
        }
        table tr th:last-child,table tr td:last-child {/*设置table右边边框*/
            border-right: 1px solid #eaeaea;
        }
        table tr td:first-child,
        table tr td:nth-child(2),
        table tr td:nth-child(3),
        table tr td:last-child{/*设置table表格每列底部边框*/
            border-bottom: 1px solid #eaeaea;
        }
       
        table tr th {
			background-color: #eaeaea;
            /*background: image(../../web/image/logo.png);*/
        }
        table tr:first-child th:first-child {
            border-top-left-radius: 20px;
        }

        table tr:first-child th:last-child {
            border-top-right-radius: 20px;
        }
        table tr:last-child td:first-child {
            border-bottom-left-radius: 20px;
        }

        table tr:last-child td:last-child {
            border-bottom-right-radius: 20px;
        }
</style>

<div class="site-login div" style="width:350px;">

    <?php
    $form = ActiveForm::begin( [
    	'id' => 'login-form-inline', 
        'type' => ActiveForm::TYPE_INLINE
    ] );
    ?>
<div id="table_wrap">
	<table class="table" width="100%">
		<thead class="table_head">
		  <tr>
			  <th width="30%"><img src="<?php echo Url::to('@web/image/logo01.png') ?>" style="height: 70px;width: 70px;"></th>
			  <th><h2><strong>裕家人</strong></h2></th>
			</tr>
		</thead>

		<tr>
			<td colspan="2">

                <br>
				<div>
					<?= $form->field($model, 'name') ?>
				</div>
				<br>
				<div>
					<?= $form->field($model, 'password')->passwordInput() ?>
				</div>
				
				<div>
					<?= $form->field($model, 'rememberMe')->checkbox() ?>
				</div>
			    <br>
				<?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
           </td>
		</tr>
	</table>
</div>
    <?php ActiveForm::end(); ?>
    
</div>