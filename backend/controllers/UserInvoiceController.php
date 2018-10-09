<?php

namespace backend\ controllers;

use Yii;
use app\models\UserInvoice;
use app\models\Up;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
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
		    $session = Yii::$app->session;
			if(isset($get['order_id']))//判断订单编号是否存在
			{
				$order_id = $get['order_id']; //接收订单编号
		        $searchModel->order_id = $order_id; //为搜索模型赋值

				$s = UserInvoice::find()//查找对应订单编号的费项是否存在
                    ->select('order_id')
                    ->where(['order_id' => $order_id])
                    ->asArray()
                    ->one();

				if ( empty( $s ) ) { //如果为空则……
    	    	    $session->setFlash( 'm_order', '2' );
    	    	    return $this->redirect( Yii::$app->request->referrer );
		    	}
			}else{ //否则返回
    	    	$session->setFlash( 'm_order', '2' );
    	    	return $this->redirect( Yii::$app->request->referrer );
			}
		}
		
		$c = $_SESSION['community']; //从会话中获取小区ID
	    
	    $comm = CommunityBasic::community(); //从模型中获取小区
		$build = CommunityBuilding::Building($c); //从模型中获取楼宇
		$number = CommunityRealestate::community_number($c); //从模型中获取单元

		$w = date('Y');
	    $y = [ $w - 3 => $w - 3,$w - 2 => $w - 2, $w - 1 => $w - 1, $w => $w, $w + 1 => $w + 1, $w + 2 => $w + 2,$w + 3 => $w + 3, ];
	    $m = [ '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', 10 => '10', 11 => '11', 12 => '12' ];
		
		if(isset($get['order_id'])){
			$searchModel->order_id = $get['order_id'];
		}

		//判断是否由来自模板头部文件的时间
		if(isset($get['one']) && isset($get['two']))
		{
			$one = date('Y-m-d', time($get['one']));
			$two = date('Y-m-d', strtotime("+1day", $get['two']));
			$time =  $one.' to '.$two;
			$searchModel->payment_time = $time;			
		}

		//判断是否是来自自动生成费项的访问
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
            $searchModel->realestate_id = $id;
        }

		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'comm' => $comm,
			'build' => $build,
			'number' => $number,
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
						if(count($sheet) != 10){
							$session->setFlash('fail','3');
							unlink($inputFileName);
							return $this->redirect( Yii::$app->request->referrer );
						}

                        $number = $sheet[ 'C' ]; //单元
                        if (!preg_match ("/^[A-Za-z]/", $number)) { //判断是否为字母
                            $number = (int)$number;
                            $number = str_pad($number, '2', '0',STR_PAD_LEFT);
                        }

						$room_name = $sheet['D']; //房号
                        if(strlen($room_name) == '3'){
                            $room_name = str_pad($room_name, '4', '0', STR_PAD_LEFT);
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
							->andwhere(['community_realestate.room_number' => $number])
							->andwhere(['community_realestate.room_name' => $room_name])
							->andwhere(['in', 'community_realestate.community_id', $c_r])
							->one();

						//获取费项
						if ($r_id) 
						{
							$y = (int)$sheet[ 'E' ];  //将年份转换为整数型，保证数据的单一性
							$m = (int)$sheet[ 'F' ]; //将月份转换为整数型，保证数据的单一性
							$m=str_pad($m,2,"0",STR_PAD_LEFT); //月份自动补0
							
							//判断费项是否存在							
							if(isset($cost_name[ $sheet[ 'G' ] ])){
								$d = $cost_name[ $sheet[ 'G' ] ];
							}else{
								$a <= $i;
								$a += 1;
								continue;
							}

							$price = (float)$sheet[ 'H' ]; //将金额数据进行处理，除了保证数据的单一性外只保留两位数
							$f = date( time() );
							$c = $r_id[ 'community' ];
							$b = $r_id[ 'building' ];
							$r = $r_id[ 'id' ];
							$s = $sheet['J'];
							
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
		$ids = $_POST;

		$is = 0; //删除条数
		//删除代码
		foreach ( $ids['ids'] as $id ) {

		    $invoice = UserInvoice::find()
            ->where(['invoice_id' => "$id"])
            ->asArray()
            ->one();

            $transaction = Yii::$app->db->beginTransaction();
            try{
                $del = new \common\models\InvoiceDel(); //实例化模型

                //模型块赋值
                $del->realestate_id = $invoice['realestate_id'];
                $del->description = $invoice['description'];
                $del->year = $invoice['year'];
                $del->month = $invoice['month'];
                $del->amount = $invoice['invoice_amount'];
                $del->order_id = $invoice['order_id'];
                $del->payment_time = $invoice['payment_time'];
                $del->invoice_notes = $invoice['invoice_notes'];

                $result = $del->save(); //保存

                if($result){ //若果删除记录保存成功则执行删除
                    $d_result = $this->findModel( $id )->delete();
                    if($d_result){ //如果删除成功则提交事务，否则测回
                        $is ++;
                    }
                }

                $transaction->commit();
            }catch(\exception $e){
                $transaction->rollback();
            }
		}
		return $is; // 返回成功删除条数
	}

	//缴费
	public function actionPay() {
		$id = Yii::$app->request->post();

		return $this->redirect( [ 'order/affirm', 'id' => $id ] );
	}

	//缴费统计列表
	public function actionSum() 
	{
		$searchModel = new \app\models\InvoiceSumSearch(); //实例化搜索模型
		
		$c = $_SESSION['community']; //从会话中获取小区ID
	    
	    $comm = CommunityBasic::find()
	    	->select(' community_name')
	    	->where(['in', 'community_id', $_SESSION['community']])
			->orderBy('community_name DESC')
	    	->indexBy('community_id')
	    	->column();
				
		$building = CommunityBuilding::find()
			->select('building_name')
			->where(['in', 'community_id', $c])	
			->distinct()						
			->orderBy('building_name')
			->indexBy('building_name')
			->column();
				
		if(!empty($_GET['InvoiceSumSearch']['payment_time'])){
			$time = explode(' to ',$_GET ['InvoiceSumSearch']['payment_time']); //分割支付时间
		    $from = reset($time); //起始年月
		    $to = end($time); //截止年月
		}elseif(!empty($this->from)) //判断并筛选支付时间
		{
			$time = explode(' to ',$this->from);
		    $from = reset($time); //起始年月
		    $to = end($time); //截止年月
			
			$fr = date('Y-m', strtotime("+13 month", strtotime($from))); //搜索起始时间加上一年
			
			$community = $this->community_id;
			
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
		
		//获取费项名称并去重复
		$c_name = CostName::find()
			->select('cost_name')
			->where(['level' => "0"])
			->indexBy('cost_name')
			->asArray()
			->column();
		
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams ); //实例化数据提供器
		$data = $dataProvider->getModels(); //获取数据提供器中的查询数据
	
		return $this->render('sum',['data' => $data,
									 'searchModel' => $searchModel,
									 'building' => $building,
									 'from' => $from,
									 'c_name' => $c_name,
									'comm' => $comm,
									 'to' => $to]);
	}
	
	//数据统计第二页面
	public function actionSumm($key, $a)
	{
		$get = $_GET['search']; //接收搜索参数
		$sum = $a; //接收总金额
		
		$from = $to = date('Y-m'); //设置默认费项期间
				
		if(empty($_GET)){
			echo "<script> alert('参数错误，请返回！');parent.location.href='./sum'; </script>";exit;
		}
		$community = $key; //提取小区
		$f = $get['from']; //获取起始日期
		$p = $get['payment_time']; //获取截止日期
				
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
			->andwhere(['user_invoice.community_id' => "$community"]);
		
		$description = $get['description']; //获取费项详情
		if($description !== '')
		{
			$ds = $ds->andwhere(['in', 'user_invoice.description', $description]);
		}
		
		$b = $get['building_id']; //获取楼宇
		if($b !== '')
		{
			$ds = $ds->andwhere(['in', 'community_building.building_name', $b]);
		}
		
		$status = $get['invoice_status']; //获取状态
		if($status !== '')
		{
			$ds = $ds->andwhere(['in', 'user_invoice.invoice_status', $get['invoice_status']]);
			
		}
		
		if(!empty($f)){
			$from = explode(' to ', $f); //拆分区间起始时间
		    $from01 = reset($from); //获取起始年
		    $from02 = end($from); //获取起始月
			
			//拆分年月
			$year01 = explode('-', $from01); //起始时间
			$year02 = explode('-', $from02); //截止时间
			
			$from = $from01;
			$to = $from02;
			
			$year_1 = reset($year01);
			$year_2 = reset($year02);
			
			$month01 = end($year01);
			$month02 = end($year02);
		}
		
		if(!empty($p)){
			$to = explode(' to ', $p); //拆分支付截止时间
		    $payment01 = reset($to); // 获取截止月
		    $payment02 = end($to); //获取截止月
			
			$from = $payment01;
			$to = $payment02;
		}

		if($p !== '')
		{
			$ds = $ds->andwhere(['between', 'user_invoice.payment_time', strtotime($payment01), strtotime($payment02)]);
		}
		
		if($f !== '')
		{
			$ds = $ds->andWhere(['and', "year >= $year_1", "month >=$month01"])
			->andWhere(['and', "year <= $year_2", "month <= $month02"]);
		}elseif(empty($f) && empty($p)){
			$ds = $ds->andFilterWhere(['year' => date('Y'), 'month' => date('m')]);
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $ds,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'community',
                    'building',
                    'number',
                    'name',
                    'year',
                    'month',
                    'description',
                    'amount',
                    'order',
                    'payment_time',
                    'status'
                ],
                'defaultOrder' => [
                    'status' => SORT_ASC,
                ]
            ],
        ]);

		return $this->render('summ',['dataProvider' => $dataProvider,
								    'from' => $from,
								    'to' => $to,
								    'sum' => $sum]);
	}

	//批量生成费项预览
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
			->andwhere(['cost_name.inv' => 1, 'cost_relation.status' => '1'])
			->andwhere(['<', 'cost_relation.from', time()])
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
		set_time_limit(0); //等待时间是10分钟
		
		$result = UserInvoice::Add();//调用数据
		return true;
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
			->andwhere( [ 'realestate_id' => $id, 'status' => '1' ] )
			->andwhere(['<', 'from', time()])
			->asArray()
			->all(); 
		
		//获取关联费项序号（二维数组）		
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
	    if(!isset($_GET['UserInvoice']))
        {
            return $this->redirect(Yii::$app->request->referrer); //如果不存在数据则返回
        }

		$b = $_GET['UserInvoice'];

		$cost = $b[ 'cost' ]; //缴费费项

		$query = ( new \yii\db\Query() )->select( [
				'community_basic.community_name',
				'community_building.building_name',
				'community_realestate.room_name',
				'community_realestate.room_number',
				'community_realestate.acreage',
				'cost_relation.realestate_id',
				'cost_name.cost_id',
				'cost_name.cost_name',
				'cost_name.formula',
			    'cost_name.inv',
				'cost_name.parent',
				'cost_name.price',
			    'cost_name.property',
			] )->from( 'cost_relation' )
			->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
			->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
			->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
			->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
			->andwhere( [ 'cost_relation.realestate_id' => $b['realestate_id'], 'cost_name.cost_id' => $cost, 'cost_relation.status' => '1' ] )
			->andwhere(['<', 'cost_relation.from', time()])
			->all();
		
		return $this->render( 'v', [
			'query' => $query,
			'b' => $b,
		] );
	}

	//单个房号批量生成费项
	public function actionOne($acreage)
	{
		$q = $_GET['b']; // 接收预览页面传过来的数据
		$co = $q[ 'cost' ]; // 生成费项的编码
		$m = $q['month']; //生成费项的月数
		
		$j = 0; //设置生成费用默认值
		$h = 0; //设置失败生成费用默认值
		
		$community = $q[ 'community_id' ];//小区编号
		$building = $q[ 'building_id' ];//楼宇编号
		$id = $q['realestate_id']; //房屋编号
		
		$f = date( time() ); //生成时间
		
		//查询费项信息
		$query = CostName::find()
			->andwhere(['in','cost_id',$co])
			->asArray()
			->all();
		
		$i = 1;
	    $d = date('Y-m', strtotime("-1 month", strtotime($q['from'])));
	    $first = $q['from'];
        $from_day = date('d', strtotime($first)); //提取预交当天日期

	    for($i; $i <= $m; $i++)
	    {
	        $date = date('Y-m', strtotime("+$i month", strtotime($d)));
				  
			foreach ( $query as $key => $qs ) {
				$c_id = $qs['cost_id']; //费项编码，将来会用到
				$description = $qs[ 'cost_name' ];//费项名称
				$formula = $qs[ 'formula' ];//计费方式 
				
				$time = explode('-',$date);
				$y = reset($time); //年
				$ms = end($time); //月
                $collagen = '1'; //设置默认计算比例
				$pr = $qs[ 'price' ]; //费项价格
				$property = $qs[ 'property' ]; //费项备注

                $day = date("t",strtotime("$date")); //指定月天数
                $days = $day; //设置默认天数
                if($date == date('Y-m', strtotime($first))){ //判断当前循环是否为第一次循环
                    $days = $day - $from_day+ 1; //计算本月剩余天数
                    $collagen = round($days/$day, '2'); //计算剩余天数占比
                }

				//判定物业费
				if ( $formula == '1' ) {
                    $p = $pr*$acreage*$collagen;
					if($p == 0){
						$h ++;
						continue;
					}
				    $price = round($p,2); //保留一位小数点
                    if($collagen < 1){
                        $property = '合计 '.$days.' 天';
                    }
				}elseif($formula == '2'){
                    $p = $pr*$days;
                    if($p == 0){
                        $h ++;
                        continue;
                    }
                    $price = round($p, 2);
                    if($days < $day){
                        $property = '合计 '.$days.' 天';
                    }
                }else{
                    $price = $pr*$collagen;
                    $price = round($price, 2);
                    echo $price;
				}

                //MySQL插入语句
				$sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status,invoice_notes)
						values ('$community','$building', '$id','$description', '$y', '$ms', '$price', '$f','0', '$property')";
				$e = Yii::$app->db->createCommand( $sql )->execute();
				
				if ($e) {//插入条数计数器
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

		$sesion = Yii::$app->session;
		$sesion->setFlash('success', $con);

		return $this->redirect(['/user-invoice', 'id' => $id]);
//		echo "<script> alert('$con');parent.location.href='/user-invoice'; </script>";
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