<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/17
 * Time: 8:39
 */

namespace api\controllers;

use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class RealestateController extends Controller
{
    //单个房屋信息
    function actionOne($realestate)
    {
        $realestate = (new Query()) //建立mysql查询语句
            ->select(["community_basic.community_name as community, community_building.building_name as building, concat(community_realestate.room_number, '单元') as number, community_realestate.room_name as room,
            community_realestate.owners_name as name, community_realestate.owners_cellphone as phone, community_realestate.acreage, from_unixtime(community_realestate.commencement) as commencement,
            from_unixtime(community_realestate.inherit) as inherit, from_unixtime(community_realestate.finish) as finish, from_unixtime(community_realestate.delivery) as delivery, 
            from_unixtime(community_realestate.decoration) as decoration, community_realestate.orientation, community_realestate.property"])
            ->from('community_realestate')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->where(['realestate_id' => "$realestate"])
            ->one();

        //遍历处理时间
        if($realestate['commencement'] == '1970-01-01 08:00:00')
        {
            $realestate['commencement'] = '';
        }

        if($realestate['inherit'] == '1970-01-01 08:00:00')
        {
            $realestate['inherit'] = '';
        }

        if($realestate['finish'] = '1970-01-01 08:00:00')
        {
            $realestate['finish'] = '';
        }

        if($realestate['delivery'] == '1970-01-01 08:00:00')
        {
            $realestate['delivery'] = '';
        }

        if($realestate['decoration'] == '1970-01-01 08:00:00')
        {
            $realestate['decoration'] = '';
        }

        $realestate = Json::encode($realestate); //数据转换
        return $realestate;
    }
}