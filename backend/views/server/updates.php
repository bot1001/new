<?php
/* @var $this yii\web\View */
$this->title = "裕达物业信息化系统";

use yii\helpers\Url;
?>
<script>
    $(document).ready(function(){
        $('#tracking').addClass("active");
		$("select").select2({dropdownCssClass: 'dropdown-inverse'});
    });
</script>
<div class="container">
	<div>
		<h4 style="float:left;">批量更新文件信息</h4>
		<a class="btn btn-primary" style="float:right;margin-top:10px;" href="<?= Url::to(['zeus/pmtracking','act'=>Yii::$app->request->get('act')]);?>">返回</a>
	</div>
    <table class="table table-bordered table-hover">
        <tr>
            <th>文件编号</th>
            <th>类型</th>
            <th>文件名称</th>
            <th>创建时间</th>
            <th>优先级</th>
            <th>备注</th>     
        </tr> 
    
    <?php foreach($list as $file): $file = (object)$file;?>
        <tr>
            <th><?=$file->id?></th>
            <td>
            <?php 
                foreach($fileType as $type){
                    $type = (object)$type;
                    if($type->value == $file->filetype){
                        echo $type->name;
                    }
                }
            ?>
            </td>
            <td><?=$file->filename?></td>
            <td><?=$file->create?></td>
            <td>
                <?php if($file->pr==0):?>
                    一般
                <?php elseif($file->pr==1):?>
                    较急
                <?php elseif($file->pr==2):?>
                    紧急
                <?php endif;?>
            </td>
            <td><?=$file->remark?></td>
        </tr>
    <?php endforeach;?>
    </table>
    <hr>
	<h4>文件转换</h4>
    <div style="clear:both" />
	<form action="<?=Url::to(['server/convertfiletype','act'=>Yii::$app->request->get('act')]);?>" method="post">
		<input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrftoken;?>" />
		<input type="hidden" name="id" value="<?=$file->id?>" />
		<div class="cos-md-3"  style="float:left">
			<strong>类型转换</strong>
		</div>
		<div class="cos-md-3" style="float:left;margin-left:20px;margin-top:-4px;">
		<select class="form-control select select-primary select-block mbl" style="float:left;" name="filetype">
            <?php foreach($fileType as $type):$type=(object)$type?>
                <option value="<?=$type->value?>"><?=$type->name?></option>
            <?php endforeach;?>
		</select>
		</div>
	
		<div class="col-md-3">
			<button class="btn btn-primary" style="margin-top:-4px;" type="submit">确定完成</button>
		</div>
        <div style="clear:both" />
	</form>
    
    <div style="clear:both" />
	<form action="<?=Url::to(['server/convertdeptype','act'=>Yii::$app->request->get('act')]);?>" method="post">
		<input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrftoken;?>" />
		<input type="hidden" name="id" value="<?=$file->id?>" />
		<div class="cos-md-3"  style="float:left">
			<strong>部门转换</strong>
		</div>
		<div class="cos-md-3" style="float:left;margin-left:20px;margin-top:-4px;">
		<select class="form-control select select-primary select-block mbl" style="float:left;" name="deptype">
            <?php foreach($depMenu as $dep):$dep=(object)$dep?>
                <option value="<?=$dep->value?>"><?=$dep->name?></option>
            <?php endforeach;?>
		</select>
		</div>
	
		<div class="col-md-3">
			<button class="btn btn-primary" style="margin-top:-4px;" type="submit">确定完成</button>
		</div>
        <div style="clear:both" />
	</form>
	<hr>
    
	<h4>文件操作</h4>
	<form action="<?=Url::to(['server/ups','act'=>Yii::$app->request->get('act')]);?>" method="post">
		<input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrftoken;?>" />
		<input type="hidden" name="id" value="<?=$file->id?>" />
		<div class="cos-md-3" style="float:left">
			<strong>状态更新</strong>
		</div>
		<div class="cos-md-3" style="float:left;margin-left:20px;margin-top:-4px;">
		<select class="form-control select select-primary select-block mbl" style="float:left;" name="select">
			
			<optgroup label="物业">
			<?php foreach($state as $i): ?>
				<?php if($i->type == 1): ?>
				<option value="<?=$i->id;?>"><?=$i->state;?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</optgroup>
			
			<optgroup label="集团">
			<?php foreach($state as $i): ?>
				<?php if($i->type == 0): ?>
				<option value="<?=$i->id;?>"><?=$i->state;?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</optgroup>
			
			<optgroup label="财务">
			<?php foreach($state as $i): ?>
				<?php if($i->type == 3): ?>
				<option value="<?=$i->id;?>"><?=$i->state;?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</optgroup>
			
			<optgroup label="其他">
			<?php foreach($state as $i): ?>
				<?php if($i->type == 2): ?>
				<option value="<?=$i->id;?>"><?=$i->state;?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</optgroup>
			
		</select>
		</div>
	
		<div class="col-md-3">
			<button class="btn btn-primary" style="margin-top:-4px;" type="submit">确定更新</button>
		</div>
	</form> 	
	<div style="clear:both" />
	<form action="<?=Url::to(['server/oks','act'=>Yii::$app->request->get('act')]);?>" method="post">
		<input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrftoken;?>" />
		<input type="hidden" name="id" value="<?=$file->id?>" />
		<div class="cos-md-3"  style="float:left">
			<strong>文件完成</strong>
		</div>
		<div class="cos-md-3" style="float:left;margin-left:20px;margin-top:-4px;">
		<select class="form-control select select-primary select-block mbl" style="float:left;" name="select">
			<?php foreach($state as $i): ?>
				<?php if($i->type == 4): ?>
				<option value="<?=$i->id;?>"><?=$i->state;?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
		</div>
	
		<div class="col-md-3">
			<button class="btn btn-primary" style="margin-top:-4px;" type="submit">确定完成</button>
		</div>
	</form>
	<div style="clear:both" />
	<form action="<?=Url::to(['server/backs','act'=>Yii::$app->request->get('act')]);?>" method="post">
		<input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrftoken;?>" />
		<input type="hidden" name="id" value="<?=$file->id?>" />
		<div class="cos-md-3">
			<strong>文件签退</strong>
		</div>
		<div class="cos-md-3 form-group">
			<textarea class="form-control" rows="3" name="backinfo"></textarea>
		</div>
		<div class="col-md-12">
			<button class="btn btn-primary" style="float:right;margin-top:10px;" type="submit">确定签退</button>
		</div>
	</form>

</div>