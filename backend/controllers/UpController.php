<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Up;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\CommunityRealestate;
use app\models\CostName;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class UpController extends Controller
{
	public function actionIndex()
	{
		$model = new Up();
		
		return $this->render('upload',
							 ['model'=>$model]);
	}
	
	public function actionR()
	{
		$model = new Up();
		 if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
				//打印文件名
				$name = $_FILES['Up']['name']['file']; //保存文件名
				$path = "./uplaod/$name"; //保存的文件路径
				
		        $inputFileType = 'Xls'; // 文件格式
		        $inputFileName = $path; //文件路径
		        $sheetname = 'Sheet1'; //设置工作表名称
		        
                $reader = IOFactory::createReader($inputFileType);
                
                $reader->setReadDataOnly(true);
		        $reader->setLoadSheetsOnly($sheetname);
                $spreadsheet = $reader->load($inputFileName);
                
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);				
            }
		 }
        //return $this->redirect(Yii::$app->request->referrer);
    }
	
	//测试读取excel文件
	public function actionRead() 
	{
		$model = new Up();
		 if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
				//打印文件名
				$name = $_FILES['Up']['name']['file']; //保存文件名
				$path = "./uplaod/$name"; //保存的文件路径
				
		        $inputFileType = 'Xlsx'; // 文件格式
		        $inputFileName = $path; //文件路径
		        $sheetname = 'Sheet1'; //设置工作表名称
		        
                $reader = IOFactory::createReader($inputFileType); //读取Excel文件
                
                $reader->setReadDataOnly(true);
		        $reader->setLoadSheetsOnly($sheetname);
                $spreadsheet = $reader->load($inputFileName);
                
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);				
            }
        }
 
		//去掉表头
		if($sheetData['1']){
			unset($sheetData['1']);
		}
		//print_r($sheetData);
		
		//查找费项信息
		if($sheetData){
		    foreach($sheetData as $sheet){
				//获取小区
		       $c_id = CommunityBasic::find()
		    	->select('community_id')
		    	->where(['community_name' => $sheet['A']])
		    	->asArray()
		    	->one();
		    	if($c_id){
					//获取楼宇
		    		$b_id = CommunityBuilding::find()
		    	          ->select(['building_id'])
		    	          ->where(['community_building.building_name' => $sheet['B']])
		    	          ->asArray()
		    	          ->one();
		    		if($b_id){
						//获取房号
		    			$r_id = CommunityRealestate::find()
		    	              ->select(['realestate_id'])
		    	              ->where(['community_id' => $b_id])
		    	              ->asArray()
		    	              ->one();
		    			//获取费项
		    			if($r_id){
							//获取费项
		    				$cost_id = CostName::find()
		    					->select('cost_id,cost_name')
		    					->where(['cost_name' => $sheet['F']])
		    					->asArray()
		    					->one();
							if($cost_id){
								//插入数据库
								$transaction = Yii::$app->db->beginTransaction(); //事务开始标记
								try{
									$y = $sheet['D'];
									$m = $sheet['E'];
									$d = $sheet['F'];
									$price = $sheet['G'];
									$f = date(time());
									$c = $c_id['community_id'];
									$b = $b_id['building_id'];
									$r = $r_id['realestate_id'];
																		
									$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status)
									values ($c,$b, $r, '$d', $y, $m, $price,$f,'0')";
									$result = Yii::$app->db->createCommand($sql)->execute();
								    $transaction->commit();//事务提交标记
									
								}catch(\Exception $e){
									print_r($e);exit;									
									$transaction->rollback();// 事务滚回标记
                                    return $this->redirect(Yii::$app->request->referrer); //返回请求页面
								}
							}else{
								echo "<script>alert('费项有误，请修改')</script>";
								exit;
							}
							//var_dump($cost_id);exit;
		    			}else{
							echo "<script>alert('房号为空，请修改数据')</script>";
							exit;
						}
		    		}else{
						echo "<script>alert('楼宇为空，请修改数据')</script>";
						exit;
					}
		    	}else{
					echo "<script>alert('小区为空，请修改数据')</script>";
					exit;
				}
		    }
		}else{
			echo "<script>alert('数据有误，请修改数据')</script>";
			exit;
		}return $this->redirect(Yii::$app->request->referrer);//返回请求页面
	}
	
	public function actionTest()
	{
		$r_name = CommunityRealestate::find()
			->select('room_name')
			->where(['community_id' => 22])
			->limit(100)
			->asArray()
			->all();
		return $this->render('upload',[
			'r_name' => $r_name
		]);
	}
}