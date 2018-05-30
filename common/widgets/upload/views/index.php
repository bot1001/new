<?php
/**
 * @see Yii中文网  http://www.yii-china.com
 * @author Xianan Huang <Xianan_huang@163.com>
 * 图片上传组件
 * 如何配置请到官网（Yii中文网）查看相关文章
 */
 
use yii\helpers\Html;
?>
<div class="per_upload_con" data-url="<?=$config['serverUrl']?>">
    <div class="per_real_img <?=$attribute?>" domain-url = "<?=$config['domain_url']?>"><?=isset($inputValue)?'<img src="'.$config['domain_url'].$inputValue.'">':''?></div>
    <div class="per_upload_img">缩略图</div>
    <div class="per_upload_text">
        <a id="<?=$attribute?>" href="javascript:;" class="btn btn-success green choose_btn glyphicon glyphicon-plus"></a>
    </div>
    <input up-id="<?=$attribute?>" id='btn' type="hidden" name="<?=$inputName?>" upname='<?=$config['fileName']?>' value="<?=isset($inputValue)?$inputValue:''?>" filetype="img" />
</div>