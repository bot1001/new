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
    public function actionIndex()
    {
        $searchModel = new WaterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
		
	public function actionNew()
    {
        $searchModel = new WaterSearch01();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('new', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
     * Creates a new WaterMeter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$session = Yii::$app->session;
    	$comm = $_SESSION[ 'user' ][ 'community' ];
		
    	if ( empty( $comm ) ) {
    		$session->setFlash( 'm', '1' );
			return $this->redirect( [ 'new' ] );
    	} else {
    		$r = CommunityRealestate::find()->select( 'community_id, building_id,realestate_id' )->where( [ 'community_id' => $comm ] );
			$r_id = $r->asArray()->all();
			
			$r_count = $r->count(); //计算房号总数
    		$count = WaterMeter::find()->andwhere( [ 'year' => date( 'Y' ) ] )->andwhere( [ 'month' => date( 'm' ) ] )->count(); //查询水表读数数量
			
    		if($count != $r_count){
				foreach ( $r_id as $id ) {
				$community = $id['community_id'];
				$building = $id['building_id'];
    			$realestate_id = $id[ 'realestate_id' ];
    			$readout = WaterMeter::find()->where( [ 'realestate_id' => $realestate_id ] )->select( 'readout' )->orderBy( 'property DESC' )->asArray()->one();

    			$read = $readout[ 'readout' ];
    			if ( empty( $read ) ) {
    				$read = 0;
    			}
    			$y = date( 'Y' );
    			$m = date( 'm' );
    			$p = date( time() );
    			
    			set_time_limit( 60 );
    			ini_set( 'memory_limit', '512M' ); // 调整PHP由默认占用内存为1024M(1GB)
				
				$model = new WaterMeter;// 实例化模型
				
				$model->community = $community;
				$model->building = $building;
				$model->realestate_id = $realestate_id;
				$model->year = $y;
				$model->month = $m;
				$model->readout = $read;
				
				$model->save(); //保存
    		    }
			}else{
				$session->setFlash( 'm', '2' ); //提示重复
				return $this->redirect(Yii::$app->request->referrer );
			}
    	}
    	return $this->redirect( Yii::$app->request->referrer );//Yii::$app->request->referrer
    }
		
	//生成水费
	public function actionFee()
	{
		$session = Yii::$app->session;
		$comm = $_SESSION[ 'user' ][ 'community' ]; //获取session中的绑定小区编号
		
		$realestate = CommunityRealestate::find()->where( [ 'community_id' => $comm ] );//检查是否生成水费
		$reale = $realestate->count(); // 获取绑定房号数量
		$realestate_id = $realestate->asArray()->all();// 获取绑定小区房屋
		$reale_id = array_column($realestate_id,'realestate_id'); // 提取房屋编号

		$m = date( 'm' );

		//计算当前生成水费数量
		$water = UserInvoice::find()->andwhere( [ 'year' => date( 'Y' ) ] )->andwhere( [ 'month' => $m ] )->andwhere( [ 'community_id' => $comm ] )->count();
		$w_meter = WaterMeter::find()->andwhere( [ 'year' => date( 'Y' ) ] )->andwhere( [ 'month' => $m ] )->andwhere( [ 'in', 'realestate_id', $reale_id ] )->count();

		if(empty($comm)){
			$session->setFlash( 'm', '1' );// 提示权限不足，返回请教界面
			return $this->redirect( Yii::$app->request->referrer );
		}elseif( $reale == $water ) {
			$session->setFlash( 'm', '3' ); // 提示已生成当月水费，不需要再次生成
			return $this->redirect( Yii::$app->request->referrer );
		}elseif($w_meter == 0){
			$session->setFlash( 'm', '4' ); // 提示当月读数为空，需要录入最新读数
			return $this->redirect( Yii::$app->request->referrer );
		}else {
			//获取水表号
			$r_id = WaterMeter::find()->select( 'realestate_id' )->distinct()->where(['in', 'realestate_id', $reale_id])->orderBy( 'property' )->asArray()->all();
			foreach ( $r_id as $id ) {
				//获取近两个月的费表读数
				$water = WaterMeter::find()->select( 'year,month,readout' )->where( [ 'realestate_id' => $id ] )->limit( 2 ) //->limit(1)
					->orderBy( 'property' )->asArray()->all();

				$i = array_column( $water, 'readout' );//提取近两个月的费表读数

				//计算差额
				$c = end( $i ) - reset( $i );
				if ( $c < 0 ) {
					$c = 0;
				}
				
				$cost = CostName::find(); //查找水费
				$c_name = $cost->select('cost_id')->where(['cost_name' => '水费'])->asArray()->all(); //获取水费编号
				$cost_id = array_column($c_name,'cost_id'); //提取水费编号
								
				//查询管理费项编号
				$c_id = CostRelation::find()->select( 'cost_id' )->andwhere( [ 'realestate_id' => $id ] )->andwhere(['in','cost_id',$cost_id])->asArray()->one();
				
				//查找关联费项单价
				$price = $cost->select( 'price,cost_name' )->where( [ 'cost_id' => $c_id[ 'cost_id' ] ] )->asArray()->one(); 
				
				$mount = $c * $price[ 'price' ];//计算金额

				//查找生成水费费项的小区和楼宇
				$info = CommunityRealestate::find()->select( 'community_id,building_id' )->where( [ 'realestate_id' => $id ] )->asArray()->one();
				$community = $info[ 'community_id' ]; //小区
				$building = $info[ 'building_id' ]; //楼宇
				$realestate = $id[ 'realestate_id' ]; // 房屋ID

				//获取年月
				$I = end( $water ); //后一月的读数信息
				$y = $I[ 'year' ]; // 年
				$M = $I[ 'month' ]; // 月
				$f = date( time() ); // 创建时间

				$d = $price[ 'cost_name' ]; //$y.'年'.$m.'月份'.

				$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status)
				values ('$community','$building', '$realestate','$d', '$y', '$M', '$mount','$f','0')";
				$result = Yii::$app->db->createCommand( $sql )->execute();
			}
		}
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
