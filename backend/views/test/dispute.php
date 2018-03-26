<?php 
$this->title = "裕达物业信息化系统";

use yii\helpers\Url;
?>
<header class="ui-header ui-header-positive ui-border-b">
    <h1>争议机制</h1>
</header>

<div class="ui-footer ui-footer-stable ui-btn-group ui-border-t">
    <script>
        $(document).ready(function(){
            $("#btnReturn").on('click',function(){
               location.href="<?= Url::to(['zeus/main'])?>"; 
            });
        });
    </script>
    <button id="btnReturn" class="ui-btn-lg ui-btn-primary" style="margin-left: 10px;">
        返回
    </button>
</div>

