<?php

namespace backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;
use app\models\WaterMeter;
use app\models\WaterSearch;//  查看费表读数
use app\models\WaterSearch01;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CommunityRealestate;
use app\models\CostRelation;
use app\models\CostName;
use app\models\UserInvoice;

/**
 * WaterController implements the CRUD actions for WaterMeter model.
 */
class WaterController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all WaterMeter models.
     * @return mixed
     */
    public function actionIndex($type)
    {
        $searchModel = new WaterSearch();
		if($type == 0)
		{
			$searchModel->type = 0;
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			//水表链接
            return $this->render('water', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
		}else{
			$searchModel->type = 1;
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			//电表链接
            return $this->render('tele', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
		}
    }
	
	//手机抄表
	public function actionPhone()
    {
		$this->layout = 'main1';
        $searchModel = new WaterSearch01();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('phone', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	//GridView 页面直接编辑代码
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'water' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => WaterMeter::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }
		
	public function actionNew($type)
    {
        $searchModel = new WaterSearch01();
		
		if($type == 0)
		{
			//水表录入链接
			$searchModel->type = 0;
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('water_in', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
		}else{
			//电表录入链接
			$searchModel->type = 1;
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('tele_in', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
		}  
    }

    /**
     * Displays a single WaterMeter model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 更新水电表读数
     */
    public function actionCreate($type, $name)
    {
		$session = Yii::$app->session;
    	$comm = $_SESSION[ 'community' ];
		
    	if ( empty( $comm ) ) {
    		$session->setFlash( 'm', '1' ); //设置权限
			return $this->redirect( [ 'new' ] );
    	} else {
    		$r = CommunityRealestate::find()
				->select( 'community_id, building_id,realestate_id' )
				->where( ['in', 'community_id', $comm] );
			$r_id = $r->asArray();
			
			$r_count = $r->count(); //计算房号总数
			
    		$count = WaterMeter::find()
				->andwhere( [ 'year' => date( 'Y' ),'month' => date( 'm' ), 'type' => $type ])
				->andwhere(['in', 'community', $comm])
				->count(); //查询水表读数数量
			
    		if($count !== $r_count){ 
				foreach($r_id ->batch(50) as $d)
				{
				    foreach ( $d as $key => $id ) 
				    {
				        $community = $id['community_id'];
				        $building = $id['building_id'];
    			        $realestate_id = $id[ 'realestate_id' ];
    			        $readout = WaterMeter::find()
				    		->where( [ 'realestate_id' => $realestate_id ] )
				    		->select( 'readout' )
				    		->orderBy( 'property DESC' )
				    		->asArray()
				    		->one();
        
    			        $read = $readout[ 'readout' ];
    			        if ( empty( $read ) ) //判断读数是否为空
				    	{
    			        	$read = 0;
    			        }
    			        $y = date( 'Y' );
    			        $m = date( 'm' );
    			        $p = date( time() );
    			        
    			        set_time_limit( 600 );
    			        ini_set( 'memory_limit', '1024M' ); // 调整PHP由默认占用内存为1024M(1GB)
						

						$sql = "insert into water_meter(community, building, realestate_id, year, month, readout, type)
						values('$community', '$building', '$realestate_id', '$y', '$m', '$read', '$type')";
						$sql = Yii::$app->db->createCommand($sql)->execute();

				    	unset($d[$key]);
    		        }
				}
			}else{
				$session->setFlash( 'm', '2' ); //提示重复
				return $this->redirect(Yii::$app->request->referrer );
			}
    	}
    	return $this->redirect( Yii::$app->request->referrer );
    }
		
	//生成费用
	public function actionFee($type, $name)
	{
		$session = Yii::$app->session;
		$comm = $_SESSION[ 'community' ]; //获取session中的绑定小区编号
		
		//检查是否生成水费
		$realestate = CommunityRealestate::find()
			->select('realestate_id, community_id as community, building_id as building')
			->where( ['in', 'community_id', $comm ] );
		
		$realestate_id = $realestate->asArray()->all();// 获取生成水费的房屋
		$reale_id = array_column($realestate_id,'realestate_id'); // 提取房屋编号
		
		$m = date("m");
		$Y = date('Y');
		$su = 0; //成功生成水费数量
		$fa = 0; //失败数量

		//计算当前当前当月存在的读数数量
		$water = UserInvoice::find()
			->andwhere( [ 'year' => $Y, 'month' => $m, 'community_id' => $comm, ] )
			->andwhere(['in', 'description', $name])
			->count();
		
		$w_meter = WaterMeter::find()
			->andwhere( [ 'year' => date( 'Y' ),'month' => $m, 'type' => $type ] )
			->andwhere( [ 'in', 'realestate_id', $reale_id ] )
			->count();

		if(empty($comm)){
			$session->setFlash( 'm', '1' );// 提示权限不足，返回请教界面
			return $this->redirect( Yii::$app->request->referrer );
		}/*elseif( $reale == $water ) {
			$session->setFlash( 'm', '3' ); // 提示已生成当月水费，不需要再次生成
			return $this->redirect( Yii::$app->request->referrer );
		}*/elseif($w_meter == 0){
			$session->setFlash( 'm', '4' ); // 提示当月读数为空，需要录入最新读数
			return $this->redirect( Yii::$app->request->referrer );
		}else {			
			foreach ( $realestate_id as $id ) {
				//获取近两个月的费表读数
				$water = WaterMeter::find()
					->select( 'year,month,readout' )
					->andwhere( [ 'realestate_id' => $id, 'type' => $type ] )
					->limit( 2 )
					->orderBy( 'property DESC' )
					->asArray()
					->all();

				$i = array_column( $water, 'readout' );//提取近两个月的费表读数

				//计算差额
				$c = reset( $i ) - end( $i );
				if ( $c < 0 ) {
					$c == 0;
				}			
				
				if($c == 0){ //如果读数等于零则终止本次循环
					continue;
				}
				
				//查找水费费项
				$cost = (new \yii\db\Query())->select('cost_name.price, cost_name.cost_name')
					->from('cost_relation')
					->join('inner join','cost_name','cost_relation.cost_id = cost_name.cost_id')
					->andwhere(['cost_relation.realestate_id' => $id['realestate_id'], 'cost_name.cost_name' => "$name"])
					->one();
				
				$mount = $c * $cost[ 'price' ];//计算金额

				$community = $id[ 'community' ]; //小区
				$building = $id[ 'building' ]; //楼宇
				$realestate = $id[ 'realestate_id' ]; // 房屋ID
 
				$I = end( $water ); //后一月的读数信息
				$date = date('Y-m', strtotime("-1 month", strtotime($I[ 'year' ].'-'.$I[ 'month' ]))); //水费自动退格一个月
				$date = explode('-', $date); //拆分年月
				
				$y = reset($date); // 年
				$M = end($date); // 月
				$f = date( time() ); // 创建时间

				$d = $cost[ 'cost_name' ]; //费项名称

				$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status)
				values ('$community','$building', '$realestate','$d', '$y', '$M', '$mount','$f','0')";
				$result = Yii::$app->db->createCommand( $sql )->execute();
				
				//计数
				if($result)
				{
					$su ++;
				}else{
					$fa ++;
				}
			}
		}
		
		$count = $su+$fa; //合计生成的条数
		$con = "成功生成$name:" . $su . "条！-  失败：" . $fa . "条 - 合计：" . $count . "条";
		$session->setFlash( 's', "$con" );// 设置生成水费结果
		return $this->redirect(Yii::$app->request->referrer);
	}

    /**
     * Updates an existing WaterMeter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WaterMeter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WaterMeter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WaterMeter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WaterMeter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
