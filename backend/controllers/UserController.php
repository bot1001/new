<?php

namespace backend\controllers;

use Yii;
use app\models\UserAccount;
use app\models\CommunityBasic;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for UserAccount model.
 */
class UserController extends Controller
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
     * Lists all UserAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new UserSearch();
		$get = $_GET;
		
		$c = $_SESSION['community'];
	
		$comm = CommunityBasic::find()
			->select('community_name')
			->where(['in', 'community_id', $c])
			->indexBy('community_name')
			->column();
		
        if(isset($get['one']))
		{
			$one = date('Y-m-d', time($get['one']));
			$two = date('Y-m-d', strtotime("+1day", $get['two']));
			$searchModel->reg_time = $one.' to '.$two;
		};
		
		if(isset($get['name']))
		{
			$searchModel->community_name = $get['name'];
		}
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'comm' => $comm
        ]);
    }

    //统计功能
    function actionSum()
    {
        $query = (new \yii\db\Query())->select([
            'count(user_account.user_id) as sum', 'community_name as community', 'company.name'])
            ->from ('user_account')
            ->join('inner join','user_relationship_realestate','user_relationship_realestate.account_id = user_account.account_id')
            ->join('inner join','community_realestate','community_realestate.realestate_id = user_relationship_realestate.realestate_id')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->join('inner join', 'company', 'company.id = community_basic.company')
            ->join('inner join','user_data','user_data.account_id = user_account.account_id')
            ->andwhere(['in', 'community_realestate.community_id', $_SESSION['community']])
            ->orderBy('community_realestate.community_id ASC')
            ->groupBy('community_realestate.community_id');

        if(isset($_GET['UserAccount'])){ //判断是否存在来自统计页面的搜索条件
            $user = $_GET['UserAccount']; //接收数据
            $company = $user['company']; //赋值总公司
            if (isset($user['user_name'])) //判断分公司是否存在
            {
                $query->andFilterWhere(['community_basic.company' => $user['user_name']]);
            }

            $community = $user['community']; //赋值小区
            $time = $user['fromdate']; //赋值统计时间

            $times = explode(' to ', $time); //分割统计时间
            $first = strtotime(reset($times)); //转换时间戳
            $second = strtotime(end($times)); //转换时间戳

            if(empty($first) && empty($second)){ //判断统计时间是否为空
                //if the two all empty, the $first will be the begining of month, the $second will be the end of month
                $first = mktime(0,0,0,date('m'),1,date('Y')); //当月起时间戳
                $second = mktime(23,59,59,date('m'),date('t'),date('Y')); //当月止时间戳
            }
            $query->andFilterWhere(['between', 'user_data.reg_time', $first, $second])
                ->andFilterWhere(['in', 'community_basic.community_id', $community]);

            $fromdate = date('Y-m-d H:i:s', $first).' to '.date('Y-m-d H:i:s', $second);
        }elseif(isset($_GET['user'])){ //判断来自Index页面筛选条件
            if($_GET['user'] != '')
            {
                $user = $_GET['user'];
                $community = $user['community_name'];
                $building = $user['building_name'];
                $number = $user['room_number'];
                if($number != ''){
                    $number = str_pad($number, '2', '0', STR_PAD_LEFT);
                }
                $time = $user['reg_time'];
                $times = explode(' to ', $time); //分割搜索时间
                $first = strtotime(reset($times)); //转换时间戳
                $second = strtotime(end($times)); //转换时间戳
                if(empty($first) && empty($second)){ //判断统计时间是否为空
                    //if the two all empty, the $first will be the begining of month, the $second will be the end of month
                    $first = mktime(0,0,0,date('m'),1,date('Y')); //当月起时间戳
                    $second = mktime(23,59,59,date('m'),date('t'),date('Y')); //当月止时间戳
                }
                $fromdate = date('Y-m-d H:i:s', $first).' to '.date('Y-m-d H:i:s', $second); //重新组合搜索时间

                $query->andFilterWhere(['between', 'user_data.reg_time', $first, $second])
                    ->andFilterWhere(['in', 'community_name', $community])
                    ->andFilterWhere(['in', 'community_building.building_name', $building])
                    ->andFilterWhere(['in', 'community_realestate.room_number', $number]);
            }
        }else{
            $first = mktime(0,0,0,date('m'),1,date('Y')); //当月起时间戳
            $second = mktime(23,59,59,date('m'),date('t'),date('Y')); //当月止时间戳
            $query->andFilterWhere(['between', 'user_data.reg_time', $first, $second]);

            $fromdate = date('Y-m-d H:i:s', $first).' to '.date('Y-m-d H:i:s', $second);
        }

        if(isset($_SESSION['pageSize'])){ //自定义每页页数
            $pageSize = $_SESSION['pageSize'];
        }else{
            $pageSize = '20';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => [
                    'name',
                    'community',
                    'sum',
                ],
                'defaultOrder' => [
                    'community' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('sum', ['dataProvider' => $dataProvider, 'fromdate' => $fromdate]);
    }

	public function actionBatchdelete()
	{
       $this->enableCsrfValidation = false;//去掉yii2的post验证
       $ids = Yii::$app->request->post();
		
       $model = new UserAccount();
		
       if($model->batchHandle($ids['ids']))
          return \yii\helpers\Json::encode(['status'=>1,'info'=>'删除成功！']);
       else
          return false;
     }
	
    /**
     * Displays a single UserAccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionV($id)
    {
        return $this->render('view1', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserAccount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserAccount model.
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
     * Finds the UserAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
