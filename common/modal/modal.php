<?php

use yii\helpers\Url;
use yii\bootstrap\Modal;

Modal::begin( [
	'id' => 'common-modal',
	'header' => '<h4 class="modal-title">默认标题</h4>',
//	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
] );

$js = <<<JS
$(".pay").click(function(){ 
        aUrl = $(this).attr('data-url');
        aTitle = $(this).attr('data-title');
        console.log(aTitle);
        console.log(aUrl);
        
        $($(this).attr('data-target')+" .modal-title").text(aTitle);
        $($(this).attr('data-target')).modal("show")
             .find(".modal-body")
             .load(aUrl); 
        return false;
   }); 
JS;
$this->registerJs( $js );

Modal::end();
?>