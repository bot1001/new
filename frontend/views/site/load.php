<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = '裕家人';
?>

<style type="text/css">
	#one{
		background: #57D1CF;
		color: aliceblue;
		text-align: center;
		height: 900px;
		border-radius: 20px;
	}
	
	.log{
		margin-top: 5px; 
        height: 55px;
        border-radius:15px;
	}
			
	.qr{
		width:160px;
		border-radius:10px;
	}
	
	#div1, #div2, #div3, #div4{
		position:relative;top:45px;
		margin-bottom: 5px;
	}
	
	copy{
		text-align: center;
	}
	#head{
		position:relative;top:35px;
		display: inline;
		width: 50%;
		font-size: 30px;
	}
	.row{
		position: relative;
		top: 35px;
	}
	
</style>

<div id="one">
	<div>
		<div id="head"><img class="log" src="/image/logo.png">裕家人</div>
				 
		<div id="div1">
		   <a href="https://itunes.apple.com/us/app/yu-jia-ren/id1113942524?mt=8"><img src="/image/ios.png"></a>
		</div>
	
		<div id="div2">
			<a href="<?=Url::to(['android']) ?>" title="安卓"><img src="/image/android.png"></a>
		</div>
			
		<div id="div3">
		    <a href="<?=Url::to(['yuda']) ?>" title="物业端"><img src="/image/android-2.png"></a>
		</div>
	</div>
	
   <div class="row">
     <hr />
     <div class="col-lg-6">	
		<div align="center">
			<img src="/image/host.png" style="width: 300px"/>
		</div>
     </div>
        <div class="col-lg-6">
           <div align="left">
           <?php echo '&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'; ?>                
           “裕家人”APP包含管家服务、便民服务、业主交流、生活超市、周边商家、跳蚤市场等功能模块的物业服务智能化系统解决方案。裕家人以社区为中心辐射“一公里微商圈”，集成包含衣、食、住、行、游、购、娱在内的各领域商户服务资源，时时推送更新活动资讯，凭借多种拓展服务，通过层层审核把关提供最放心的便捷选择。主要服务业主及住户，打造物业服务、社区交流与社区商业服务的O2O平台。
	        </div>
            <div id="div4">
	        	<img src="/image/QR.png" class="qr" />
	        	<p>扫描二维码下载裕家人APP</p>
	        	<p>适用于：iOS 9.0+ / Android 4.0+ </p>
	        </div>
       </div>
     </div>
     
	<div align="center">
	    <a href="http://www.gxydwy.com">裕家人 2.0</a>
	    <br />
		&copy;<?= date('Y') ?> 裕达物业 桂ICP备14007933号-2
	</div>
</div>