<?php

namespace backend\ controllers;

use Yii;
use app\ models\ CostRelation;
use app\ models\ CostName;
use app\ models\ CostRelationSearch;
use app\ models\ CommunityBasic;
use app\ models\ CommunityBuilding;
use app\ models\ CommunityRealestate;
use yii\ helpers\ Json;
use yii\ helpers\ ArrayHelper;
use yii\ web\ Controller;
use yii\ web\ NotFoundHttpException;
use yii\ filters\ VerbFilter;

/*use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';*/

/**
 * CostRelationController implements the CRUD actions for CostRelation model.
 */
class CostrelationController extends Controller {
	/**
	 * @inheritdoc
	 */
	public function behaviors() 
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => [ 'POST' ],
				],
			],
		];
	}

	/**
	 * Lists all CostRelation models.
	 * @return mixed
	 */
	public function actionIndex() 
	{
		$searchModel = new CostRelationSearch();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		] );
	}
	
	//检查用户是否登录
	public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login']);
            return false;
        }
        return true;
    }

	//来自房屋列表的绑定费用查询
	public function actionIndex1( $realestate_id ) 
	{
		$searchModel = new CostRelationSearch();
		
		$searchModel->realestate_id = $realestate_id;
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		] );
	}

	public function actionAdd() 
	{
		$a = Yii::$app->request->get();
		//print_r($a);
		$community = $a[ 'community_id' ];
		$building_id = $a[ 'building_id' ];
		$realestate_id = $a[ 'realestate_id' ];
		$cost_id = $a[ 'cost_id' ];
		$price = $a[ 'price' ];
		$y = $a[ 'y' ];
		$m = $a[ 'm' ];
		$f = date( time() );
		$sql = "insert into user_invoice_del(community,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status) values ('$community','$building_id', '$realestate_id','$cost_id', '$y', '$m', '$price','$f','0')";
		Yii::$app->db->createCommand( $sql )->execute();
		echo "插入成功！";
	}

	/**
	 * Displays a single CostRelation model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView( $id ) 
	{
		return $this->renderAjax( 'view', [
			'model' => $this->findModel( $id ),
		] );
	}

	/**
	 * Creates a new CostRelation model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() 
	{
		$model = new CostRelation();
		
		$model->load($_POST);
        /*if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }*/

		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( [ 'index' ] );
		} else {
			return $this->renderAjax( '_form', [
				'model' => $model,
			] );
		}
	}

	//三级联动之 楼宇
	public function actionB( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) {
			$id = $_POST[ 'depdrop_parents' ];
			$list = CommunityBuilding::find()->where( [ 'community_id' => $id ] )->all();
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
	
	//三级联动之 楼宇2
	public function actionB2( $selected = null ) 
	{
		print_r($_POST[ 'depdrop_parents' ]);exit;
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{
			$id = array_column($_POST[ 'depdrop_parents' ], 'community_id');
			$list = CommunityBuilding::find()->where( ['in', 'community_id', $id ] )->all();
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

	//三级联动之 房号（一）
	public function actionR( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{
			$id = $_POST[ 'depdrop_parents' ];
			$list = CommunityRealestate::find()
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
	public function actionRe( $selected = null ) {
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) {
			$id = $_POST[ 'depdrop_parents' ];
			$list = CommunityRealestate::find()
				->where( [ 'building_id' => $id ] )
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

	//多级联动之 费项金额price
	public function actionP( $selected = null ) {
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) {
			$id = $_POST[ 'depdrop_parents' ];
			$l = CostName::find()->andwhere( [ 'cost_id' => $id ] )->all();
			foreach ( $l as $li );
			$i = $li[ 'cost_id' ];
			$list = CostName::find()->where( [ 'parent' => $i ] )->orderBy('price ASC')->all();
			//print_r($list);die;
			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'cost_id' ], 'name' => $account[ 'price' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'cost_id' ];
					}
					if ( $account[ 'cost_id' ] == $selected ) {
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

	public function actionCreate1($id) 
	{
		$model = new CostRelation();
		
		$c = $_SESSION['user']['community'];
	    $array = Yii::$app->db->createCommand('select cost_id,cost_name from cost_name where level = 0')->queryAll();
	    $cost = ArrayHelper::map($array,'cost_id','cost_name');
 
		//获取小区和楼宇编号
		$r_info = CommunityRealestate::find()
			->select('community_id,building_id')
			->where(['realestate_id' => $id])
			->asArray()
			->one();
		//获取小区名称
		$c_info = CommunityBasic::find()
			->select('community_name,community_id')
			->where(['community_id' => $r_info['community_id']])
			->asArray()
			->all();
		//获取楼宇名称
		$b_info = CommunityBuilding::find()
			->select('building_name,building_id')
			->where(['building_id' => $r_info['building_id']])
			->asArray()
			->all();
	    //获取房号信息
	    $array1 = CommunityRealestate::find()
	       ->select('realestate_id,room_name')
	       ->where(['realestate_id' => $id])
	       ->All();
	    	   
	   $community = ArrayHelper::map($c_info,'community_id','community_name');//提取房号信息
	   $building = ArrayHelper::map($b_info,'building_id','building_name');//提取房号信息
	   $num = ArrayHelper::map($array1,'realestate_id','room_name');//提取房号信息
		
		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( Yii::$app->request->referrer );
		} else {
			return $this->renderAjax( 'form', [
				'model' => $model,
				'num' => $num,
				'cost' => $cost,
				'community' => $community,
				'building' => $building,
			] );
		}
	}

	/**
	 * Updates an existing CostRelation model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate( $id ) 
	{
		$model = $this->findModel( $id );

		//获取房屋序号
		$r_id = Costrelation::find()->select('realestate_id as id,community,building_id,cost_id')->where(['id' => $id])->asArray()->one();
		//获取房屋信息
		$r_info = CommunityRealestate::find()->select('room_number,room_name')->where(['realestate_id' => $r_id])->asArray()->one();
		
		//获取小区
		$c_info = CommunityBasic::find()->select('community_name,community_id')->where(['community_id' => $r_id['community']])->asArray()->all();
		
		//获取楼宇
		$b_info = CommunityBuilding::find()->select('building_name,building_id')->where(['building_id' => $r_id['building_id']])->asArray()->all();
		
		$array = Yii::$app->db->createCommand('select cost_id,cost_name from cost_name where level = 0')->queryAll();
	    $cost = ArrayHelper::map($array,'cost_id','cost_name');
		
		//获取房屋相关信息
		$a = CommunityRealestate::find()->select('realestate_id,room_name')->where(['realestate_id' => $r_id['id']])->asArray()->all();
		
		$community = ArrayHelper::map($c_info,'community_id','community_name');//提取小区信息
		$building = ArrayHelper::map($b_info,'building_id','building_name');//提取楼宇信息
		$num = ArrayHelper::map($a,'realestate_id','room_name');//提取房号信息  [realestate_id] => 15190 [room_name] => 1002 ) 
		
		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( [ 'index' ] );
		} else {
			return $this->renderAjax( 'form', [
				'model' => $model,
				'num' => $num,
				'cost' => $cost,
				'community' => $community,
				'building' => $building,
			] );
		}
	}

	/**
	 * Deletes an existing CostRelation model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete( $id ) {
		$this->findModel( $id )->delete();

		return $this->redirect( [ 'index' ] );
	}

	/**
	 * Finds the CostRelation model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return CostRelation the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id ) {
		if ( ( $model = CostRelation::findOne( $id ) ) !== null ) {
			return $model;
		} else {
			throw new NotFoundHttpException( 'The requested page does not exist.' );
		}
	}
}