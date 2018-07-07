<?php

namespace backend\controllers;

use app\models\CommunityRealestate;
use Yii;

class TestController extends \yii\web\Controller
{	
	public function actionIndex()
	{
        ini_set( 'memory_limit', '2048M' );// 调整PHP由默认占用内存为2048M(2GB)
        set_time_limit(0);
		$community=$_SESSION['community'];
		$i = 0;
		$f = 0;
		foreach ($community as $c){
		    $realestate=CommunityRealestate::find()
                ->select('realestate_id as id, community_id as community, building_id as building ,room_number as number, room_name as name')
                ->where(['in', 'community_id', $c])
                ->asArray()
                ->all();
		    foreach ($realestate as $r){
		        $id=$r['id'];
		        $number=$r['number'];
		        $name=$r['name'];

		        if(strlen($number) < '2'){
		            $number = str_pad($number, 2, '0', STR_PAD_LEFT);
                }

		        $room=explode('-', $name);
		        $room01 = reset($room); //拆分后的单元 UPDATE `yuhomepeople`.`community_realestate` SET `room_name`='0702' WHERE  `realestate_id`=32784;
                $room01=str_pad($room01,2,"0",STR_PAD_LEFT); //不满2位数的自动补0

                $room02 = end($room); //拆分后的房号

                if($number === $room01 || strlen($room02) == '3')
                {
                    $room02=str_pad($room02,4,"0",STR_PAD_LEFT); //不满4位数的自动补0
                    $sql = "update ignore community_realestate set room_name= '$room02' , room_number = '$number' where  realestate_id=$id";
                    $result = Yii::$app->db->createCommand( $sql )->execute();
                }

                if(isset($result))
                {
                    if($result == '1'){
                        $i++; //计算成功修改个数
                    }else{
                        $f++;
                    }
                }else{
                    $f++; //计算失败个数
                }
            }
        }

        echo '成功修改: '.$i.' 个'.'失败：'.$f.' 个';
	}	
}
