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
use yii\data\Pagination;

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
		
		if(isset($get['order'])) //判断来自订单列表的查询
		{ 
			if(isset($get['order_id']))
			{ //判断订单编号是否存在
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
		
		$c = $_SESSION['community']; //从回话中获取小区ID
	    
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
		
		//判断来自统计页面的的参数
		if(isset($_GET['community']))
		{
			$community = $_GET['community'];
			$searchModel->community_id = $community;
		}
		
		if(isset($_GET['description']))
		{
			$description = $_GET['description'];
			$searchModel->description = $description;
		}
		
		//判断是否存在时间
		if(isset($_GET['from']) && isset($_GET['to']))
		{
			$from = $_GET['from']; //接收起始时间
			$to = $_GET['to']; //接收截止时间
			$t = $from.' to '.$to; //拼接时间
			$searchModel->payment_time = $t;
		}
		//判断来自统计页面费项查询止
		
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
							if(isset($cost_name[ $sheet[ 'F' ] ])){
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
							
							$model->setScenario('up'); //设置场景
							//赋值给模型
							$model->community_id = $c;
							$model->building_id = $b;
							$model->realestate_id = $r;
							$model->description = $d;
							$model->year = $y;
							$model->month = $m;
							
							if($price == '0' || $price == ''){ //判断导入金额是否为0或为空
								$a += 1;
								continue;
							}else{
								$model->invoice_amount = $price;
							}
							
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

		$is = 0; //删除条数
		//删除代码
		foreach ( $ids as $id );
		foreach ( $id as $i ) {
			$this->findModel( $id )->delete();
			$is ++;
		}
		return $is;
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
		
		$c = $_SESSION['community'];
				
		//获取小区
	    $comm = CommunityBasic::find()
	    	->select('community_name, community_id')
	    	->where(['in', 'community_id', $c])
	    	->orderBy('community_name')
			->indexBy('community_id')
	    	->column();
		$building = CommunityBuilding::find()
			->select('building_name')
			->distinct()
			->where(['in', 'community_id', $c])							
			->orderBy('building_name')
			->indexBy('building_name')
			->column();
				
		if(!empty($_GET['InvoiceSumSearch']['payment_time'])){
			$time = explode(' to ',$_GET ['InvoiceSumSearch']['payment_time']); //分割支付时间
		    $from = reset($time); //起始年月
		    $to = end($time); //截止年月
		}elseif(!empty($_GET['InvoiceSumSearch']['from'])) //判断并筛选支付时间
		{
			$time = explode(' to ',$_GET ['InvoiceSumSearch']['from']);
		    $from = reset($time); //起始年月
		    $to = end($time); //截止年月
			
			$fr = date('Y-m', strtotime("+13 month", strtotime($from))); //搜索起始时间加上一年
			
			$community =$_GET['InvoiceSumSearch']['community_id'];
			
			$c = count($community); //计算小区数量
			
			//判断选择小区数量和时间长度
			if(strtotime($fr) <= strtotime($to) && ($c >10 || $community == ''))
			{
				$session = Yii::$app->session;
    	    	$session->setFlash( 'fail', '0' );
				return $this->redirect(Yii::$app->request->referrer);
			}
			
		}else{
			$from = date('Y-m', time());
		    $to = date('Y-m', time());
		}
				
		//判断并赋值楼宇
		if(!empty($_GET['InvoiceSumSearch']['building_id']))
		{
			$b = $_GET['InvoiceSumSearch']['building_id'];
		}else{
			$b = '';
		}
		
		//判断并赋值缴费项目
		if(!empty($_GET['InvoiceSumSearch']['description']))
		{
			$description = $_GET['InvoiceSumSearch']['description'];
		}else{
			$description = '';
		}
		
		//判断并赋值缴费状态
		if(!empty($_GET['InvoiceSumSearch']['invoice_status']))
		{
			$status = $_GET['InvoiceSumSearch']['invoice_status'];
		}else{
			$status = '';
		}
		
		//获取费项名称并去重复
		$c_name = CostName::find()
			->select('cost_name')
			->distinct()
			->indexBy('cost_name')
			->asArray()
			->column();
		
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams ); //实例化数据提供器
		$data = $dataProvider->getModels(); //获取数据提供器中的查询数据
	
		return $this->render('sum',['data' => $data,
									 'searchModel' => $searchModel,
									 'comm' => $comm,
									 'building' => $building,
									 'from' => $from,
									 'c_name' => $c_name,
									 'description' => $description,
									 'status' => $status,
									 'b' => $b,
									 'to' => $to]);
	}
	
	//数据统计第二页面
	public function actionSumm()
	{
		if(empty($_GET['key'])){
			echo "<script> alert('参数错误，请返回！');parent.location.href='./sum'; </script>";exit;
		}
		$community_id = $_GET['key'];
		$f = $_GET['f']; //获取起始日期
		$first = reset($f); //获取起始年
		$month01 = $f['1']; //获取起始月
		$from = implode('-', $f); //拼接起始时间
		
		$t = $_GET['t']; //获取截止日期
		$secend = reset($t); // 获取截止月
		$month02 = $t['1']; //获取截止月
		$to = implode('-', $t); //拼接截止时间
		
		$sum = $_GET['sum']; //获取总金额
		$ds = (new \yii\db\Query())
			->select('user_invoice.invoice_id as id, community_basic.community_name as community, community_building.building_name as building,
			community_realestate.room_number as number, community_realestate.room_name as name, user_invoice.description as description,
			user_invoice.invoice_amount as amount, user_invoice.payment_time as payment_time,
			user_invoice.year as year, user_invoice.month as month,
			user_invoice.order_id as order, user_invoice.invoice_status as status')
			->from('user_invoice')
			->join('inner join', 'community_basic', 'community_basic.community_id = user_invoice.community_id')
			->join('inner join', 'community_building', 'community_building.building_id = user_invoice.building_id')
			->join('inner join', 'community_realestate', 'community_realestate.realestate_id = user_invoice.realestate_id')
			->andwhere(['user_invoice.community_id' => "$community_id"]);
		
		$description = $_GET['description']; //获取费项详情
		if($description !== '')
		{
			$ds = $ds->andwhere(['in', 'user_invoice.description', $description]);
		}
		
		$b = $_GET['b']; //获取楼宇
		if($b !== '')
		{
			$ds = $ds->andwhere(['in', 'community_building.building_name', $b]);
		}
		
		$status = $_GET['status']; //获取状态
		if($status !== '')
		{
			$ds = $ds->andwhere(['in', 'user_invoice.invoice_status', $_GET['status']]);
			
		}
		
		if(count($f) == '3' && count($t) == '3')
		{
			//缴费时间转换成时间戳
			$from02 = strtotime($from);
			$to02 = strtotime($to);
			
			$ds = $ds->andwhere(['between', 'user_invoice.payment_time', $from02, $to02]);
			$dss = $ds;
		}elseif(count($f) == '2' && count($t) == '2'){
			$ds = $ds->andwhere(['between', 'user_invoice.year', $first, $secend]);
			if($first == $secend){
				$ds = $ds->andwhere(['between', 'user_invoice.month', $month01, $month02]);
				$dss = $ds;
			}else{
				$d = $ds->orderBy('user_invoice.year DESC, user_invoice.month DESC')->all();//第一次获取数据
			    $date = UserInvoice::Summ($d, $f, $t);//过滤多余数组
			    $id = array_column($date, 'id');//提取过滤之后的数据ID
	            $dss = $ds->andwhere(['in', 'user_invoice.invoice_id', $id]);
			}
		}
		
        $count = $dss->count();// 计算总数
		
       $pagination = new Pagination(['totalCount' => $count]);// 创建分页对象
       
       // 使用分页对象来填充 limit 子句并取得文章数据
       $data = $ds->offset($pagination->offset)
                   ->limit($pagination->limit)
                   ->all();
		
		$c = $_SESSION['community'];
		
		//获取小区
	    $comm = CommunityBasic::find()
	    	->select('community_name, community_id')
	    	->where(['in', 'community_id', $community_id])
	    	->orderBy('community_name')
			->indexBy('community_id')
	    	->column();
		$building = CommunityBuilding::find()
			->select('building_name')
			->distinct()
			->where(['in', 'community_id', $community_id])							
			->orderBy('building_name')
			->indexBy('building_name')
			->column();
		
		return $this->render('summ',['data' => $data,
									'pagination' => $pagination,
									'comm' => $comm,
									'building' => $building,
								    'from' => $from,
								    'to' => $to,
								    'sum' => $sum,
								    'count' => $count]);
	}

	//批量生产费项预览
	public function actionNew() 
	{
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
			->andwhere( [ 'community_realestate.community_id' => $_SESSION[ 'community' ] ] )
			->andwhere(['cost_name.inv' =>1])
		    ->limit( 20 )
		    ->all();

		return $this->renderAjax( 'new', [
			'query' => $query,
		] );
	}

	//批量生成费项
	public function actionAdd() 
	{
		ini_set( 'memory_limit', '2048M' );// 调整PHP由默认占用内存为2048M(2GB)
		set_time_limit(900); //等待时间是10分钟
		
		$query = ( new\ yii\ db\ Query() )->select( [
			'community_realestate.community_id',
			'community_realestate.building_id',
			'cost_relation.realestate_id',
			'community_realestate.acreage',
			'cost_relation.cost_id',
			'cost_name.cost_name',
			'cost_name.parent',
			'cost_name.price',
			'cost_name.property',
		] )
			->from( 'cost_relation' )
			->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
			->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
			->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
			->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
			->andwhere( ['in', 'community_realestate.community_id', $_SESSION[ 'community' ] ] )
			->andwhere(['cost_name.inv' =>1])
			->all();
		
		$y = date( 'Y' );
		$m = date( 'm' );
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
			$notes = $q['property'];
						
			if ( $description == "物业费" || $description == "空调运作费" || $description == "水电周转金" ) {
				$p = $price*$acreage;
				$price = round($p,1); //保留一位小数点
			}
			
			//出入语句
			$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status, invoice_notes)
			values ('$community','$building', '$realestate','$description', '$y', '$m', '$price','$f','0','$notes')";
			$result = Yii::$app->db->createCommand( $sql )->execute();

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
			    'cost_name.property',
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
		$query = CostName::find()->select('cost_id,cost_name,price,inv, property')->where(['in','cost_id',$co])->asArray()->all();
		
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
				
				$pr = $qs[ 'price' ]; //费项价格
				$property = $qs[ 'property' ]; //费项备注
				
				//判定物业费
				if ( $description == "物业费" || $description == "空调运作费" || $description == "水电周转金" ) {
					$p = $pr*$acreage;
				    $price = round($p,1); //保留一位小数点
				}else{
					$price = $pr;
				}
				
				//MySQL插入语句
				$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status,invoice_notes)
						values ('$community','$building', '$id','$description', '$y', '$ms', '$price', '$f','0', '$property')";
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

		return $this->redirect( Yii::$app->request->referrer );
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