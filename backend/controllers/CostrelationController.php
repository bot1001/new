<?php

namespace backend\ controllers;

use Yii;
use app\models\CostRelation;
use app\models\CostName;
use app\models\CostRelationSearch;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\CommunityRealestate;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;

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
		
		//来自房屋列表的绑定费用查询
		if(isset($_GET['realestate_id']))
		{
			$searchModel->realestate_id = $_GET['realestate_id'];
		}
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );
		
		$c = $_SESSION['community'];
		
		$comm = CommunityBasic::find()
			->select('community_name, community_id')
			->where(['in', 'community_id', $c])
			->orderBy('community_name')
			->indexBy('community_id')
			->column();
	
		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'comm' => $comm
		] );
	}
	
	//GridView页面直接编辑
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'relation' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => CostRelation::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
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

	public function actionAdd() 
	{
		$a = Yii::$app->request->get();
		
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
		
		if ( $model->load( Yii::$app->request->post() )) {
			$post = $_POST['CostRelation']; // 接收传递过来的信息
			
			$community = $post['community'];
			$building = $post['building_id'];
			$realestate_id = $post['realestate_id'];
			$price = $post['price'];
			$cost = $post['cost_id'];
			$from = $post['from'];
			$status = $post['status'];
			$property = $post['property'];
			
			$i=0; //成功次数
			$f=0; //失败次数
			
			foreach($realestate_id as $realestate)
			{
				$model = new CostRelation(); //实例化模型，每次循环必须实例化一次
				
				$model->community = $community;
			    $model->building_id = $building;			    
			    $model->cost_id = $cost;
			    $model->from = $from;
			    $model->status = $status;
			    $model->property = $property;
				$model->realestate_id = $realestate;
				
			    $e = $model->save(); //保存数据
				if($e){ //自动计数
					$i ++;
				}else{
					$f ++;
				}
			}
			
			$count = $i+$f; // 生成条数综合
			
			$session = Yii::$app->session; //实例化flash信息
			$session->setFlash('success', "成功：$i 条，失败： $f 条，合计：$count 条");
			
			return $this->redirect( [ 'index', ] );
		} else {
			return $this->renderAjax( '_form', [
				'model' => $model,
			] );
		}
	}

	//三级联动之 楼宇
	public function actionB( $selected = null ) 
	{
		$community_id = $_POST['depdrop_parents']['0']; //接收小区编号
		
		//获取楼宇ID
		$building_id = CommunityBuilding::find()
			->select('building_id')
			->where(['in', 'community_id', $community_id])
			->asArray()
			->one();

		//将楼宇编号添加到session中，以备由单元获取房号时使用
		$_SESSION['building_id'] = $building_id;
		
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
			$number = $_POST['depdrop_all_params']['number'];
			$id =$_POST['depdrop_all_params']['building'];
			$list = CommunityRealestate::find()
				->andwhere( ['in', 'building_id', $id] )
				->andwhere( ['in', 'room_number', $number] )
				->all();

			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'room_name' ], 'name' => $account[ 'room_name' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'room_name' ];
					}
					if ( $account[ 'room_name' ] == $selected ) {
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
			$list = CommunityRealestate::find()
				->andwhere( ['in', 'building_id', $id] )
				->andwhere( ['in', 'room_number', $number] )
				->all();

			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'realestate_id' ], 'name' => $account[ 'room_name' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'realestate_id' ];
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
		
	//三级联动之 单元
	public function actionNumber( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{
			$id = $_POST[ 'depdrop_parents' ];
			
			$list = CommunityRealestate::find()->select('room_number')->where( ['in', 'building_id', $id ] )->distinct()->all();
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
		
		$relation = (new \yii\db\Query)
			->select('cost_name.parent, cost_name.cost_name, cost_name.property')
			->from('cost_name')
			->join('inner join', 'cost_relation', 'cost_name.cost_id = cost_relation.cost_id')
			->where(['cost_relation.realestate_id' => "$id"])
			->all();
		
		$r = array_column($relation, 'parent');
		
	    $cost = CostName::find()
			->select('cost_name, cost_id')
			->andwhere(['level' => "0"])
			->andwhere(['not in', 'cost_id', $r])
			->orderBy('cost_id ASC')
			->indexBy('cost_id')
			->column();
 
		//获取小区和楼宇编号
		$r_info = (new \yii\db\Query())
			->select('community_realestate.community_id as community_id, community_basic.community_name as community_name,
			community_realestate.building_id as building_id, community_building.building_name as building_name,
			community_realestate.realestate_id as realestate_id, community_realestate.room_name as room_name')
			->from('community_realestate')
			->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
			->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
			->where(['realestate_id' => $id])
			->all();
	    	   
	   $community = ArrayHelper::map($r_info,'community_id','community_name');//提取房号信息
	   $building = ArrayHelper::map($r_info,'building_id','building_name');//提取房号信息
	   $num = ArrayHelper::map($r_info,'realestate_id','room_name');//提取房号信息
				
		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( Yii::$app->request->referrer );
		} else {
			return $this->renderAjax( 'form', [
				'model' => $model,
				'num' => $num,
				'cost' => $cost,
				'community' => $community,
				'building' => $building,
				'relation' => $relation,
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
		$r_id = Costrelation::find()
			->select('realestate_id as id,community,building_id,cost_id')
			->where(['id' => $id])
			->asArray()
			->one();
		//获取房屋信息
		$r_info = CommunityRealestate::find()
			->select('room_number,room_name')
			->where(['realestate_id' => $r_id])
			->asArray()
			->one();
		
		//获取小区
		$c_info = CommunityBasic::find()
			->select('community_name,community_id')
			->where(['community_id' => $r_id['community']])
			->asArray()
			->all();
		
		//获取楼宇
		$b_info = CommunityBuilding::find()
			->select('building_name,building_id')
			->where(['building_id' => $r_id['building_id']])
			->asArray()
			->all();
		
		$array = Yii::$app->db->createCommand('select cost_id,cost_name from cost_name where level = 0')
			->queryAll();
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