<?php

namespace backend\controllers;

use Yii;
use app\models\CommunityRealestate;
use app\models\Up;
use app\models\CommunityRealestatenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\HouseInfo;

/**
 * CommunityRealestateController implements the CRUD actions for CommunityRealestate model.
 */
class CommunityRealestateController extends Controller
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
     * Lists all CommunityRealestate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommunityRealestatenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	//导入业主资料
	public function actionImport()
	{
		$model = new Up();
		return $this->renderAjax( 'impo',[
			'model' => $model] );
	}
	
	//接收导入文件并导入数据
	public function actionRead()
	{
		$model = new Up();
		$session = Yii::$app->session;
		
		ini_set( 'memory_limit', '2048M' );// 调整PHP由默认占用内存为1024M(1GB)
		set_time_limit(300);
		
		$a = 0; // 更新条数
		$b = 0; // 插入条数
		$c = 0; // 失败条数
		$h = 0; //重复条数
		$comm = $_SESSION['user']['community'];
		
		if ( Yii::$app->request->isPost ) {
			$model->file = UploadedFile::getInstance( $model, 'file' );
			$name = $_FILES[ 'Up' ][ 'name' ][ 'file' ]; //保存文件名
			$g = pathinfo( $name, PATHINFO_EXTENSION );
			
			$n = date(time()).".$g"; //新文件名
			//判断文件格式，确定是否上传
			if ( $g == 'xls' || $g == 'xlsx' ) {
				if ( $model->upload() ) {
					$path = "./uplaod/$name"; //保存的文件路径
					rename("uplaod/$name","uplaod/$n"); //修改文件名称
					if ( $g == 'xls' ) { // 判断文件格式
						$inputFileType = 'Xls';
					} elseif ( $g == 'xlsx' ) {
						$inputFileType = 'Xlsx';
					} else {
						echo '文件类型错误';
						exit;
					}

					$inputFileName = "uplaod/$n"; //文件路径
					$sheetname = 'Sheet1'; //设置工作表名称
					$reader = IOFactory::createReader( $inputFileType ); //读取Excel文件

					$reader->setReadDataOnly( true );
					$reader->setLoadSheetsOnly( $sheetname );
					$spreadsheet = $reader->load( $inputFileName );

					$sheetData = $spreadsheet->getActiveSheet()->toArray( null, true, true, true );
					unset( $sheetData[ '1' ] ); //去掉表头
					$i = count($sheetData);
				}
				
				foreach($sheetData as $sheet)
				{
					sleep(0.01);
					//print_r($sheet['N']);exit;
					if(count($sheet) != 14){
							$session->setFlash('fail','3');
						    unlink($inputFileName);
							return $this->redirect( Yii::$app->request->referrer );
						}
					
					//判断为空字段
					if(empty($sheet['M'])){
						$sheet['M'] = 0;
					}
					if(empty($sheet['N'])){
						$sheet['N'] = 0;
					}
					
					//获取小区
					$c_id = CommunityBasic::find()->select( 'community_id' )->where( [ 'community_name' => $sheet[ 'A' ] ] )->asArray()->one();
					
					if(empty($comm)){
						//管理员账号操作
						if($c_id){
						    //获取楼宇
						    $b_id = CommunityBuilding::find()->select( [ 'building_id' ] )
						    		->andwhere( [ 'community_building.building_name' => $sheet[ 'B' ] ] )
						    		->andwhere(['community_id' => $c_id['community_id']])->asArray()->one();
						    if($b_id){
						    	//获取房屋编号
						    	$r_id = CommunityRealestate::find()->select( [ 'realestate_id' ] )
                                    ->andwhere(['community_id' => $c_id['community_id']])
                                    ->where( [ 'building_id' => $b_id['building_id'] ] )
                                    ->andwhere( [ 'room_name' => $sheet[ 'D' ] ] )
                                    ->asArray()
                                    ->one();
						    	if(!empty($r_id)){
						    		//更新数据库记录
						     		$d = CommunityRealestate::updateAll([
										'acreage' => (float)$sheet[ 'G' ], 
										'finish' => strtotime($sheet['H']),
										'delivery' => strtotime($sheet['I']),
										'decoration' => strtotime($sheet['J']),
										'orientation' => $sheet['M'],
										'property' => $sheet['N']
									], 'realestate_id = :id',[':id' => $r_id['realestate_id']]);
									
									$house = New HouseInfo(); //实例化模型
									//print_r($r_id);
									$house->realestate = $r_id['realestate_id'];
									$house->name = $sheet['E'];
									$house->phone = $sheet['F'];
									$house->IDcard = $sheet['K'];
									$house->address = $sheet['L'];
									
									$e = $house->save();
									
									if($e){
										echo '1';
									}else{
										echo '保存失败！';
									}
									
						    		if ( $d ) {
			                        	$a <= $i;
			                        	$a += 1;
			                        }else {
			                        	$h <= $i;
			                        	$h += 1;
			                        }
						    	}elseif(!empty($c_id) && !empty($b_id)){
						    		//插入新记录
						    		$model = new CommunityRealestate();
						    		$model->community_id = (int)$c_id['community_id']; //小区
						    		$model->building_id = (int)$b_id['building_id']; //楼宇
						    		$model->room_number = (int)$sheet['C']; //单元
						    		$model->room_name = $sheet['D']; //房号
						    		$model->owners_name = $sheet['E'];
						    		$model->owners_cellphone = (int)$sheet['F']; //手机号码
						    		$model->acreage = (float)$sheet['G']; //面积
						    					
						    		$e = $model->save(); //保存
						    		if( $e ){
						    			$b <= $i;
						    			$b += 1;
						    		}else{
						    			$c <= $i;
						    			$c += 1;
						    		}
						    	}else{
						    		$c += 1;
						    		continue;
						    	}
						    }else{
						    	$c += 1;
						    	continue;
						    }
					    }else{
					    	$c += 1;
					    	continue;
					    }
					}else{
						//前台账户操作
					    if($c_id && $c_id['community_id'] == $comm){ 
					    	//获取楼宇
					    	$b_id = CommunityBuilding::find()->select( [ 'building_id' ] )
					    			->andwhere( [ 'community_building.building_name' => $sheet[ 'B' ] ] )
					    			->andwhere(['community_id' => $c_id['community_id']])->asArray()->one();
					    	if($b_id){
							    //获取房屋编号
							    $r_id = CommunityRealestate::find()->select( [ 'realestate_id' ] )
                                    ->andwhere(['community_id' => $c_id['community_id']])
                                    ->where( [ 'building_id' => $b_id['building_id'] ] )
                                    ->andwhere( [ 'room_name' => $sheet[ 'D' ] ] )
                                    ->asArray()
                                    ->one();
							    if(!empty($r_id)){
							    	//更新数据库记录
							    	$d = CommunityRealestate::updateAll(['acreage' => (float)$sheet[ 'G' ]], 'realestate_id = :id',[':id' => $r_id['realestate_id']]);
							    	if ( $d ) {
			                        	$a <= $i;
			                        	$a += 1;
			                        }else {
			                        	$h <= $i;
			                        	$h += 1;
			                        }
							    }/*elseif(!empty($c_id) && !empty($b_id) && $comm == $c_id['community_id']){
									
							    	//插入新记录
							    	$model = new CommunityRealestate();
							    	$model->community_id = (int)$c_id['community_id']; //小区
							    	$model->building_id = (int)$b_id['building_id'];//楼宇
							    	$model->room_number = (int)$sheet['C']; //单元
							    	$model->room_name = $sheet['D'];//房号
							    	$model->owners_name = $sheet['E'];
							    	$model->owners_cellphone = $sheet['F'];//手机号码
							    	$model->acreage = (float)$sheet['G'];
							    				
							    	$e = $model->save(); //保存
							    	if( $e ){
							    		$b <= $i;
							    		$b += 1;
							    	}else{
							    		$c <= $i;
							    		$c += 1;
							    	}
							    }*/else{
							    	$c += 1;
							    	continue;
							    }
						     }else{
					        		$c += 1;
					        		continue;
					        	}
					    }else{
					    	$c += 1;
					    	continue;
					    }
					}
				}
			}else{
				$session->setFlash('fail','2');//设置文件格式错误提醒
				return $this->redirect( Yii::$app->request->referrer ); //返回请求页面
			}
		}
		if(isset($inputFileName)){
			unlink($inputFileName);
		}
		$con = "成功更新记录：" . $a . "条！-  插入：". $b . "条！ - 失败：". $c . "条！ - 重复". $h. "条 - 合计：" . $i . "条";
		//echo "<script> alert('$con');parent.location.href='./'; </script>";
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
	
	//下载业主资料模板
	public function actionDownload()
    {
        return \Yii::$app->response->sendFile('./uplaod/template/info.xlsx');
    }
	
	//GridView 页面直接编辑代码
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'reale' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => CommunityRealestate::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }

	//批量删除
	public function actionDel()
	{
        //$this->enableCsrfValidation = false;//去掉yii2的post验证
        /*$ids = Yii::$app->request->post();

		foreach($ids as $id);
		//删除代码(一开始就不应该存在)
		foreach($id as $i){
			$this->findModel($id)->delete();
		}
		return $this->redirect(Yii::$app->request->referrer);*/
   }
	
    /**
     * Displays a single CommunityRealestate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CommunityRealestate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CommunityRealestate();
		$model->setScenario('create');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect( Yii::$app->request->referrer );
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CommunityRealestate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->setScenario('update');
		
		$b = CommunityBuilding::find()->select('building_id,building_name')->where(['building_id' => $model['building_id']])->asArray()->all();
	    $c = CommunityBasic::find()->select('community_name,community_id')->where(['community_id' => $model['community_id']])->asArray()->all();
	    $comm = ArrayHelper::map($c,'community_id','community_name');
	    $bu = ArrayHelper::map($b,'building_id','building_name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect( Yii::$app->request->referrer );
        } else {
            return $this->renderAjax('form', [
                'model' => $model,
				'comm' => $comm,
				'bu' => $bu
            ]);
        }
    }

    /**
     * Deletes an existing CommunityRealestate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CommunityRealestate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CommunityRealestate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CommunityRealestate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
