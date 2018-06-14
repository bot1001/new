<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Community;
use common\models\Building;
use common\models\Realestate;
use yii\helpers\Json;

class RealestateController extends Controller
{
	//获取楼宇（一）
	public function actionB( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) {
			$id = $_POST[ 'depdrop_parents' ];
			$list = Building::find()->where( [ 'community_id' => $id ] )->all();
			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'building_id' ], 'name' => $account[ 'building_name' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'building_id' ];
					}
					if ( $account[ 'building_id' ] == $selected ) {
						$isSelectedIn = true;
					}
				}
				if ( !$isSelectedIn ) {
					$selected = $first;
				}
				echo Json::encode( [ 'output' => $out, 'selected' => $selected ] );
				return;
			}
		}
		echo Json::encode( [ 'output' => '', 'selected' => '' ] );
	}
	
	//三级联动之 单元
	public function actionB2( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{
			$id = $_POST[ 'depdrop_parents' ];
			
			$list = Realestate::find()->select('room_number')->where( ['in', 'building_id', $id ] )->distinct()->all();
			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'room_number' ], 'name' => $account[ 'room_number' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'room_number' ];
					}
					if ( $account[ 'room_number' ] == $selected ) {
						$isSelectedIn = true;
					}
				}
				if ( !$isSelectedIn ) {
					$selected = $first;
				}
				echo Json::encode( [ 'output' => $out, 'selected' => $selected ] );
				return;
			}
		}
		echo Json::encode( [ 'output' => '', 'selected' => '' ] );
	}

	//三级联动之 房号（一）
	public function actionR( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{
			$id = $_POST[ 'depdrop_parents' ];
			$list = Realestate::find()
				->andwhere( [ 'building_id' => $id ] )
				->all();
			
			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'room_name' ], 'name' => $account[ 'room_name' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'room_number' ];
					}
					if ( $account[ 'realestate_id' ] == $selected ) {
						$isSelectedIn = true;
					}
				}
				if ( !$isSelectedIn ) {
					$selected = $first;
				}
				echo Json::encode( [ 'output' => $out, 'selected' => $selected ] );
				return;
			}
		}
		echo Json::encode( [ 'output' => '', 'selected' => '' ] );
	}

	//三级联动之 房号（二）
	public function actionRe( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{			
			$number = $_POST['depdrop_all_params']['number'];
			$id =$_POST['depdrop_all_params']['building'];
			$list = Realestate::find()
				->andwhere( ['in', 'building_id', $id] )
				->andwhere( ['in', 'room_number', $number] )
				->all();

			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'realestate_id' ], 'name' => $account[ 'room_name' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'room_number' ];
					}
					if ( $account[ 'realestate_id' ] == $selected ) {
						$isSelectedIn = true;
					}
				}
				if ( !$isSelectedIn ) {
					$selected = $first;
				}
				echo Json::encode( [ 'output' => $out, 'selected' => $selected ] );
				return;
			}
		}
		echo Json::encode( [ 'output' => '', 'selected' => '' ] );
	}
	
	//修改当前进入的房号
	public function actionChange($k)
	{
		$house = $_SESSION['house'];
		$_SESSION['home'] = $house[$k];
		
		return true;
	}
	
	function actionHome()
	{
		return $this->render('index');
	}
}