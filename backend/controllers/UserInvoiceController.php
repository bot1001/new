<?php

namespace backend\ controllers;

use Yii;
use app\models\UserInvoice;
use app\models\Up;
use yii\web\UploadedFile;
use app\models\OrderBasic;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\CommunityRealestate;
use app\models\CostName;
use app\models\CostRelation;
use app\models\UserInvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * UserInvoiceController implements the CRUD actions for UserInvoice model.
 */
class UserInvoiceController extends Controller 
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
					'delete' => [ 'POST' ],
				],
			],
		];
	}

	/**
	 * Lists all UserInvoice models.
	 * @return mixed
	 */
	public function actionIndex() 
	{
		$get = $_GET;
		$searchModel = new UserInvoiceSearch();
		
		if(isset($get['order'])){ //判断来自订单列表的查询
			if(isset($get['order_id'])){ //判断订单编号是否存在
				$order_id = $get['order_id']; //接收订单编号
		        $searchModel->order_id = $order_id; //为搜索模型赋值
				//查找对应订单编号的费项是否存在
				$s = UserInvoice::find()->select('order_id')->where(['order_id' => $order_id])->asArray()->one();
				if ( empty( $s ) ) { //如果为空则……
    	    	    $session = Yii::$app->session;
    	    	    $session->setFlash( 'm_order', '2' );
    	    	    return $this->redirect( Yii::$app->request->referrer );
		    	}
			}else{ //否则返回
				$session = Yii::$app->session;
    	    	$session->setFlash( 'm_order', '2' );
    	    	return $this->redirect( Yii::$app->request->referrer );
			}
		}
		
		$c = $_SESSION['community'];
	    
	    $comm = CommunityBasic::find()
	    	->select(' community_name')
	    	->where(['in', 'community_id', $_SESSION['community']])
			->orderBy('community_name DESC')
	    	->indexBy('community_id')
	    	->column();
		
		$build = CommunityBuilding::find()
	    	->select('building_name')
			->where(['in', 'community_id', $_SESSION['community']])
	    	->distinct()
	    	->indexBy('building_name')
	    	->column();
		
		$w = date('Y');
	    $y = [ $w - 3 => $w - 3,$w - 2 => $w - 2, $w - 1 => $w - 1, $w => $w, $w + 1 => $w + 1, $w + 2 => $w + 2,$w + 3 => $w + 3, ];
	    $m = $m = [ '01' => '01月', '02' => '02月', '03' => '03月', '04' => '04月', '05' => '05月', '06' => '06月', '07' => '07月', '08' => '08月', '09' => '09月', 10 => '10月', 11 => '11月', 12 => '12月' ];
		
		if(isset($get['order_id'])){
			$searchModel->order_id = $get['order_id'];
		}
		
		if(isset($get['one']) && isset($get['two']))
		{
			$one = date('Y-m-d', time($get['one']));
			$two = date('Y-m-d', strtotime("+1day", $get['two']));
			$time =  $one.' to '.$two;
			$searchModel->payment_time = $time;			
		}
		
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'comm' => $comm,
			'build' => $build,
			'y' => $y,
			'm' => $m
		] );
	}

	//下载业主资料模板
	public function actionDownload()
    {
        return \Yii::$app->response->sendFile('./uplaod/template/invoice.xlsx');
    }
	
	//Excel文件导入
	public function actionImport() 
	{
		$model = new Up();
		return $this->renderAjax( 'impo', [
			'model' => $model,
		] );
	}

	//接收并导入excel文件
	public function actionRead() 
	{
		$model = new Up();
		$session = Yii::$app->session;
		
		ini_set( 'memory_limit', '2048M' );// 调整PHP由默认占用内存为2048M(2GB)
		set_time_limit(600); //等待时间是10分钟
		
		$t = 0; // 插入条数
		$a = 0; // 失败条数
		
		if ( Yii::$app->request->isPost ) {
			$model->file = UploadedFile::getInstance( $model, 'file' );
			$name = $_FILES[ 'Up' ][ 'name' ][ 'file' ]; //保存文件名
			$g = pathinfo( $name, PATHINFO_EXTENSION );

			$n = date(time()).".$g";//新文件名
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
				//费项状态
				$status = [ '欠费' => '0', '银行' => '1', '线上' => '2', '刷卡' => '3', '优惠' => '4', '政府' => '5', '现金' => '6', '建行' => '7' ];
				
				//账户关联小区
				$c_r = $_SESSION['community'];
				
				//判断是否存在关联小区
				if(reset($c_r) == ''){
					$session->setFlash('fail', '5');
					return $this->redirect(Yii::$app->request->referrer);
				}
				
				//获取费项
				$cost_name = CostName::find()
					->select( 'cost_name' )
					->where( [ 'level' => '0' ] )
					->indexBy('cost_name')
					->column();
				
				//查找费项信息
				if ( $sheetData ) {
					foreach ( $sheetData as $sheet ) 
					{
						sleep(0.01);
						if(count($sheet) != 9){
							$session->setFlash('fail','3');
							unlink($inputFileName);
							return $this->redirect( Yii::$app->request->referrer );
						}
						//验证房号是否存在
						$r_id = (new \yii\db\Query())
							->select( 'community_basic.community_id as community, 
							           community_building.building_id as building, 
							           community_realestate.realestate_id as id' )
							->from('community_realestate')
							->join('inner join', 'community_basic', 'community_realestate.community_id = community_basic.community_id')
							->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
							->andwhere( [ 'community_basic.community_name' => $sheet[ 'A' ] ] )
							->andwhere(['community_building.building_name' => $sheet[ 'B' ]])
							->andwhere(['community_realestate.room_name' => $sheet[ 'C' ]])
							->andwhere(['in', 'community_realestate.community_id', $c_r])
							->one();
						
						//获取费项
						if ($r_id) 
						{
							$y = (int)$sheet[ 'D' ];  //将年份转换为整数型，保证数据的单一性
							$m = (int)$sheet[ 'E' ]; //将月份转换为整数型，保证数据的单一性
							$m=str_pad($m,2,"0",STR_PAD_LEFT); //月份自动补0
							
							//判断费项是否存在							
							if($cost_name[ $sheet[ 'F' ] ]){
								$d = $cost_name[ $sheet[ 'F' ] ];
							}else{
								$a <= $i;
								$a += 1;
								continue;
							}
														
							$price = (float)$sheet[ 'G' ]; //将金额数据进行处理，除了保证数据的单一性外只保留两位数
							$f = date( time() );
							$c = $r_id[ 'community' ];
							$b = $r_id[ 'building' ];
							$r = $r_id[ 'id' ];
							$s = $sheet['I'];
							
							//验证提交的费项状态
							if(isset($status[$s]))
							{
								$i_s = $status["$s"];
							}else{
								$a <= $i;
								$a += 1;
								continue;
							}
								
							$model = new UserInvoice(); //实例化模型
							//赋值给模型
							$model->community_id = $c;
							$model->building_id = $b;
							$model->realestate_id = $r;
							$model->description = $d;
							$model->year = $y;
							$model->month = $m;
							$model->invoice_amount = $price;
							$model->create_time = $f;
							$model->invoice_status = $i_s;
								
							$e = $model->save(); //保存
							
							if($e){
								$t <= $i;
								$t += 1;
							}else{
								$a <= $i;
								$a += 1;
							}
						} else {
							$a += 1;
							continue;
						}
						
					}
				} else {
					unlink($inputFileName);
					return $this->redirect( Yii::$app->request->referrer ); //返回请求页面
				}
			}else{
				$session->setFlash('fail','2');
				return $this->redirect( Yii::$app->request->referrer ); //返回请求页面
			}
		}
		if(isset($inputFileName)){
			unlink($inputFileName);
		}
		$con = "成功导入：". $t . "条！ - 失败：". $a . "条 - 合计：" . $i . "条";
		echo "<script> alert('$con');parent.location.href='./'; </script>";
	}

	//批量删除
	public function actionDel() 
	{
		$ids = Yii::$app->request->post();

		//删除代码
		foreach ( $ids as $id );
		foreach ( $id as $i ) {
			$this->findModel( $id )->delete();
		}
		return $this->redirect( Yii::$app->request->referrer );//返回请求页面
	}

	//缴费
	public function actionPay() {
		$id = Yii::$app->request->post();

		if ( empty( $id ) ) {
			$session = Yii::$app->session;
			$session->setFlash('fail','1');
			return $this->redirect( Yii::$app->request->referrer );
		} else {
			return $this->redirect( [ 'order/affirm', 'id' => $id ] );
		}
	}

	//缴费统计列表
	public function actionSum() 
	{
		$searchModel = new \app\models\InvoiceSumSearch(); //实例化搜索模型
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams ); //实例化数据提供器
		$data = $dataProvider->getModels(); //获取数据提供器中的查询数据
				
		$c = $_SESSION['community'];
		
		//获取小区
	    if(empty($c)){
	    	$comm = CommunityBasic::find()
	    		->select('community_name, community_id')
				->orderBy('community_name')
	    		->indexBy('community_id')
	    		->column();
	    }else{
	    	$comm = CommunityBasic::find()
	    		->select('community_name, community_id')
	    		->where(['in', 'community_id', $c])
	    		->orderBy('community_name')
				->indexBy('community_id')
	    		->column();
	    }
				
		if($_GET)
		{
			$get = $_GET;
			$d = UserInvoice::Sum($get);
			$from = $d['from'];
			$to = $d['to'];
		}else{
			$from = date('Y-m-d', time() );
		    $to = date('Y-m-d', time() );
		}
		
		//获取费项名称并去重复
		$c_name = CostName::find()
			->select('cost_name')
			->distinct()
			->indexBy('cost_name')
			->asArray()
			->column();
		
		$filter = UserInvoice::Filter($data,$c_name, $comm);
		echo '<pre />';
		print_r($filter);exit;
		
		return $this->render('test',['data' => $data,
									 'searchModel' => $searchModel,
									 'comm' => $comm,
									 'from' => $from,
									 'c_name' => $c_name,
									 'to' => $to]);
	}

	//缴费统计查询
	public function actionSearch() 
	{
		$model = new UserInvoice;
		
		$comm = $_SESSION['user']['community']; //从session中获取小区代号
		if($_POST)
		{
		    $fr = Yii::$app->request->post();//接受传过来的时间
		    foreach ( $fr as $f ); //遍历时间
		    $time = $f['from']; //提取时间
		    
			if(empty($time)){
				$from = strtotime(date('Y-m-d'));
		        $to = date( time() );
			}else{
				$t = explode(' - ',$time); //分割时间
				$from = strtotime( reset($t) ); //转换时间戳
		        $to = strtotime( end($t) ); //转换时间戳
			}
			
			//判断楼宇是否存在
		    if(!empty($f['building_id'])){
		    	$b = $f['building_id'];
		    }elseif(empty($comm)){
		    	$b = CommunityBuilding::find()->select('building_id')->asArray()->all();
		    }else{
				$b = CommunityBuilding::find()->select('building_id')->where(['community_id' => $comm])->asArray()->all();
			}
			//判断小区是否存在
		    if(!empty($f['community_id'])){
		    	$c_id = $f['community_id'];
		    }elseif(empty($comm)){
		    	$c_id = CommunityBasic::find()->select('community_id')->asArray()->all();
		    }else{
				$c_id = CommunityBasic::find()->select('community_id')->where(['community_id' => $comm])->asArray()->all();
			}
		}else{
			$from = strtotime(date('Y-m-d'));
		    $to = date( time() );
			
			//判断楼宇是否存在
		    if(empty($comm)){
		    	$b = CommunityBuilding::find()->select('building_id')->asArray()->all();
		    }else{
				$b = CommunityBuilding::find()->select('building_id')->where(['community_id' => $comm])->asArray()->all();
			}
			//判断小区是否存在
		    if(empty($comm)){
		    	$c_id = CommunityBasic::find()->select('community_id')->asArray()->all();
		    }else{
				$c_id = CommunityBasic::find()->select('community_id')->where(['community_id' => $comm])->asArray()->all();
			}
		}

		$C = CommunityBasic::find()->select('community_id, community_name');
		
		
        if(empty($comm)){
			$name = $C->asArray()->All();
			if(!empty($f['community_id'])){
				$community = $C->where(['community_id' => $f['community_id']])->asArray()->All();
			}else{
				$community = $C->asArray()->All();
			}
			
		    $c = ArrayHelper::map($name,'community_id', 'community_name');//组合小区信息
			//print_r($name);exit;
			$sum = UserInvoice::find()
				->select('community_id, order_id, description, invoice_amount')
				->andwhere(['in', 'community_id', $c_id])
				->andwhere(['in', 'building_id', $b])
				->andwhere( [ 'between', 'payment_time', $from, $to ] )
				->orderBy('description')
				->asArray()
				->all();
        }else{
            $community = $C->where(['community_id' => $comm])
				->asArray()
				->All();
			$sum = UserInvoice::find()
				->select('community_id, description, order_id,invoice_amount')
				->andwhere(['in', 'community_id', $c_id])
				->andwhere(['in', 'building_id', $b])
				->andwhere(['community_id' => $comm])
				->andwhere( [ 'between', 'payment_time', $from, $to ] )
				->orderBy('description')
				->asArray()
				->all();
			$c = ArrayHelper::map($community,'community_id', 'community_name');//组合小区信息
		}
		$d = array_column($sum,'description'); //提取费项名称
		$de = array_unique($d); //费项名称去重复
		$in = array_sum( array_column($sum, 'invoice_amount') ); //求总金额
        
		$cost = CostName::find()->select('cost_name')->where(['level' => '0'])->asArray()->all();
        
		if ( !$from ) {
			$from = date( time() );
		}

		if ( !$to ) {
			$to = date( time() );
		}
		
		return $this->render( 'sum', [
			'model' => $model,
			'community' => $community,
			'from' => $from,
			'to' => $to,
			'in' => $in,
			'sum' => $sum,
			'comm' => $comm,
			'cost' => $cost,
			'de' => $de,
			'c' => $c
		] );
	}

	//批量生产费项预览
	public function actionNew() 
	{
		if($_SESSION[ 'user' ][ 'name' ] == 'admin'){
			$query = ( new\ yii\ db\ Query() )->select( [
			'community_realestate.community_id',
			'community_basic.community_name',
			'community_realestate.building_id',
			'community_building.building_name',
			'community_realestate.acreage',
			'community_realestate.room_name',
			'community_realestate.room_number',
			'cost_relation.realestate_id',
			'cost_relation.cost_id',
			'cost_name.cost_name',
			'cost_name.parent',
			'cost_name.price',
		] )->from( 'cost_relation' )
			->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
			->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
			->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
			->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
			->where(['or not like','cost_name.cost_name',['水费']]) // 去除水费
			//->where( [ 'community_realestate.community_id' => $_SESSION[ 'user' ][ 'community' ] ] )
			->andwhere(['cost_name.inv' =>1])
		    ->limit( 20 )
		    ->all();
		}else{
			$query = ( new\ yii\ db\ Query() )->select( [
			'community_realestate.community_id',
			'community_basic.community_name',
			'community_realestate.building_id',
			'community_building.building_name',
			'community_realestate.acreage',
			'community_realestate.room_name',
			'community_realestate.room_number',
			'cost_relation.realestate_id',
			'cost_relation.cost_id',
			'cost_name.cost_name',
			'cost_name.parent',
			'cost_name.price',
		] )->from( 'cost_relation' )
			->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
			->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
			->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
			->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
			->andwhere( [ 'community_realestate.community_id' => $_SESSION[ 'user' ][ 'community' ] ] )
			->andwhere(['or not like','cost_name.cost_name',['水费']])
			->andwhere(['cost_name.inv' =>1])
		    ->limit( 20 )
		    ->all();
		}

		return $this->renderAjax( 'new', [
			'query' => $query,
		] );
	}

	//批量生成费项
	public function actionAdd() 
	{
		ini_set( 'memory_limit', '2048M' );// 调整PHP由默认占用内存为2048M(2GB)
		set_time_limit(900); //等待时间是10分钟
		
		if($_SESSION[ 'user' ][ 'name' ] == 'admin'){
			$query = ( new\ yii\ db\ Query() )->select( [
			'community_realestate.community_id',
			'community_realestate.building_id',
			'cost_relation.realestate_id',
			'community_realestate.acreage',
			'cost_relation.cost_id',
			'cost_name.cost_name',
			'cost_name.parent',
			'cost_name.price',
		] )
			->from( 'cost_relation' )
			->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
			->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
			->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
			->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
			->andwhere(['or not like','cost_name.cost_name',['水费']]) // 去除水费
			->andwhere(['cost_name.inv' =>1])
			->all();
		}else{
			$query = ( new\ yii\ db\ Query() )->select( [
			'community_realestate.community_id',
			'community_realestate.building_id',
			'cost_relation.realestate_id',
			'community_realestate.acreage',
			'cost_relation.cost_id',
			'cost_name.cost_name',
			'cost_name.parent',
			'cost_name.price',
		] )
			->from( 'cost_relation' )
			->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
			->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
			->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
			->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
			->andwhere( [ 'community_realestate.community_id' => $_SESSION[ 'user' ][ 'community' ] ] )
			->andwhere(['or not like','cost_name.cost_name',['水费']]) // 去除水费
			->andwhere(['cost_name.inv' =>1])
			->all();
		}
		
		$y = date( 'Y' );
		$m = date( 'm' );
		//$m += 0;
		$f = date( time() );
		$a = 0;
		$b = 0;
		$i = count( $query );
		
		foreach ( $query as $q ) {
			sleep(0.01);
			$community = $q[ 'community_id' ];
			$building = $q[ 'building_id' ];
			$realestate = $q[ 'realestate_id' ];
			$cost = $q[ 'cost_id' ];
			$description = $q[ 'cost_name' ];
			$d = $y . "年" . $m . "月份" . $description;
			$price = $q[ 'price' ];
			$acreage = $q[ 'acreage' ];
						
			if ( $description == "物业费" ) {
				$p = $price*$acreage;
				$price = number_format($p, 1);
				$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status)
				values ('$community','$building', '$realestate','$description', '$y', '$m', '$price','$f','0')";
				$result = Yii::$app->db->createCommand( $sql )->execute();
			} else {
				$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status)
				values ('$community','$building', '$realestate','$description', '$y', '$m', '$price','$f','0')";
				$result = Yii::$app->db->createCommand( $sql )->execute();
			}

			if ( $result ) {
				$a <= $i;
				$a += 1;
			} else {
				$b <= $i;
				$b += 1;
			}
		}
		$con = "成功生成费项" . $a . "条！-  失败：" . $b . "条 - 合计：" . $i . "条";
		echo "<script> alert('$con');parent.location.href='./'; </script>";

	}

	//单个房号生成费项条件筛选
	public function actionC( $id ) 
	{
		$model = new UserInvoice;
		$model->setScenario('c');
		//获取房屋信息
		$reale = (new \yii\db\Query())
			->select(['community_realestate.realestate_id as r_id','community_realestate.community_id as c_id','community_realestate.building_id as b_id',
					  'community_realestate.room_number as number','community_realestate.room_name as name',
			'community_basic.community_name as community','community_building.building_name as building'])
			->from('community_realestate')
			->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
			->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
			->where(['community_realestate.realestate_id' => $id])
			->all();
		
		$comm = ArrayHelper::map($reale, 'c_id', 'community');
		$build = ArrayHelper::map($reale, 'b_id', 'building');
		$number = ArrayHelper::map($reale, 'r_id', 'number');
		$name = ArrayHelper::map($reale, 'r_id', 'name');

		$cost_id = CostRelation::find()
			->select( 'cost_id' )
			->where( [ 'realestate_id' => $id ] )
			->asArray()
			->all(); //获取关联费项序号（二维数组）
		
		$c_id = array_column( $cost_id, 'cost_id' ); //提取关联费项序号
		$cost_info = CostName::find()
			->select( 'cost_id, cost_name' )
			->andwhere( [ 'in','cost_id', $c_id ] )
			->asArray()
			->all();//获取关联费项信息
		
		$cost = ArrayHelper::map( $cost_info, 'cost_id', 'cost_name' );// 重组关联费项信息

		if(empty($cost)){
			echo '此房号暂无关联费项，请点击楼宇关联！';
		}else{
			return $this->renderAjax( 'form', [
		    	'model' => $model,
				'comm' => $comm,
				'build' => $build,
				'number' => $number,
				'name' => $name,
		    	'cost' => $cost,
		    ] );
		}
	}

	//单个房号生成费项预览
	public function actionV() 
	{		
		$b = $_GET['UserInvoice'] ;
		
		$community = $b['community_id']; //小区编号
		$m = $b[ 'month' ]; //缴费月数
		$cost = $b[ 'cost' ]; //缴费费项
		$from = $b['from']; //起始缴费月份

		$query = ( new\ yii\ db\ Query() )->select( [
				'community_basic.community_name',
				'community_building.building_name',
				'community_realestate.room_name',
				'community_realestate.room_number',
				'community_realestate.acreage',
				'cost_relation.realestate_id',
				'cost_name.cost_id',
				'cost_name.cost_name',
			    'cost_name.inv',
				'cost_name.parent',
				'cost_name.price',
			] )->from( 'cost_relation' )
			->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
			->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
			->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
			->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
			->where( [ 'cost_relation.realestate_id' => $b['realestate_id'], 'cost_name.cost_id' => $cost ] )
			->all();
		
		return $this->render( 'v', [
			'query' => $query,
			'b' => $b,
		] );
	}

	//单个房号批量生成费项
	public function actionOne() 
	{
		$q = $_GET['b']; // 接收预览页面传过来的数据
		$acreage = $_GET['acreage']; //房屋面积
		$co = $q[ 'cost' ]; // 生成费项的编码
		$m = $q['month']; //生成费项的月数
		
		$j = 0; //设置生成费用默认值
		$h = 0; //设置失败生成费用默认值
		
		$community = $q[ 'community_id' ];//小区编号
		$building = $q[ 'building_id' ];//楼宇编号
		$id = $q['realestate_id']; //房屋编号
		
		$f = date( time() ); //生成时间
		
		//查询费项信息
		$query = CostName::find()->select('cost_id,cost_name,price,inv')->where(['in','cost_id',$co])->asArray()->all();
		
		$i = 1;
	    $d = date('Y-m', strtotime("-1 month", strtotime($q['from'])));
	    for($i; $i <= $q['month']; $i++)
	    { 
	        $date = date('Y-m', strtotime("+$i month", strtotime($d)));
				  
			foreach ( $query as $key => $qs ) {
				$c_id = $qs['cost_id']; //费项编码，将来会用到
				$description = $qs[ 'cost_name' ];//费项名称
				
				$time = explode('-',$date);
				$y = reset($time); //年
				$ms = end($time); //月
				
				$price = $qs[ 'price' ]; //费项价格
				
				if ( $description == "物业费" ) {
					//判定物业费
					$p = $price*$acreage;
				    $price = number_format($p, 1);
				}
				
				//MySQL插入语句
				$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status)
						values ('$community','$building', '$id','$description', '$y', '$ms', '$price','$f','0')";
				$e = Yii::$app->db->createCommand( $sql )->execute();
				
				
				//插入条数计数器
				if ($e) {
				    $j <= $i;
			    	$j += 1;
			    } else {
			    	$h <= $i;
			    	$h += 1;
			    }
				if($qs['inv'] == 0)
				  {
					  unset($query[$key]);
				  }
            }	
		}
        $count = $j+$h; //合计生成的条数
		$con = "成功生成缴费记录" . $j . "条！-  失败：" . $h . "条 - 合计：" . $count . "条";
		echo "<script> alert('$con');parent.location.href='/user-invoice'; </script>";

	}

	public function actions() 
	{
		return ArrayHelper::merge( parent::actions(), [
			'invoice' => [ // identifier for your editable action
				'class' => EditableColumnAction::className(), // action class name
				'modelClass' => UserInvoice::className(), // the update model class
				'outputValue' => function ( $model, $attribute, $key, $index ) {},
				'ajaxOnly' => true,
			]
		] );
	}

	/**
	 * Displays a single UserInvoice model.
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
	 * Creates a new UserInvoice model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new UserInvoice();
		$model->setScenario('update');

		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( 'index' );
		} else {
			return $this->renderAjax( 'update', [
				'model' => $model,
			] );
		}
	}

	/**
	 * Updates an existing UserInvoice model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate( $id ) {
		$model = $this->findModel( $id );

		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( [ 'view', 'id' => $model->invoice_id ] );
		} else {
			return $this->render( 'update', [
				'model' => $model,
			] );
		}
	}

	/**
	 * Deletes an existing UserInvoice model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete( $id ) {
		$this->findModel( $id )->delete();

		return $this->redirect( [ 'index' ] );
	}

	/**
	 * Finds the UserInvoice model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserInvoice the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id ) {
		if ( ( $model = UserInvoice::findOne( $id ) ) !== null ) {
			return $model;
		} else {
			throw new NotFoundHttpException( 'The requested page does not exist.' );
		}
	}
}