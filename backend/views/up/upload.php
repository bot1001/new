<?php

use kartik\form\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],
								 //'type' => ActiveForm::TYPE_INLINE,
								'action' => '/user-invoice/read'
								]) ?>

    <?php // $form->field($model, 'file')->fileInput() ?>

    <button>上传</button>

<?php ActiveForm::end() ?>

<?php 
	foreach($r_name as $name){
		$n = $name['room_name'];
		
		//print_r($n); echo '<hr />';
	}
   
?>
