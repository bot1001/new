<?php

namespace backend\controllers;

use Yii;
use app\models\SysUser;
use common\models\Company;
use app\models\SysRole;
use app\models\SysCommunity;
use app\models\SysuserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;

/**
 * SysuserController implements the CRUD actions for SysUser model.
 */
class SysuserController extends Controller
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
     * Lists all SysUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SysuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	//GridView页面直接编辑
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'sysuser' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => Sysuser::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }
    /**
     * Displays a single SysUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SysUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SysUser();
		$model->setScenario('create'); //设置场景$model->setScenario('up');

		$company = Company::find()
			->select('name, id')
			->orderBy('name')
			->indexBy('id')
			->column();

		$role = SysRole::find()
			->select('name, id')
			->where(['not like', 'name', 'admin'])
			->orderBy('name')
			->indexBy('id')
			->column();

        if ($model->load(Yii::$app->request->post())/* && $model->save()*/)
		{
			$post = $_POST['SysUser']; // 接收传递过来的数据

            $transaction=$model->db->beginTransaction();
            try{
				$model = new SysUser(); //实例化用户模型

				//模型块赋值
                $model->company = $post['company'];
			    $model->community = $post['community'];
			    $model->role = $post['role'];
			    $model->real_name = $model->name = $post['name'];
			    $model->phone = $post['phone'];
			    $model->new_pd = md5($post['new_pd']);
			    $model->status = $post['status'];
			    $model->comment = $post['comment'];

                $m = $model->save(); //保存数据

				if($m){
				    $user = new SysCommunity(); //实例化关联小区模型

				    $id = Yii::$app->db->getLastInsertId(); //获取最新插入的记录ID
			        $user->sys_user_id = $id;
			        $user->community_id = $post['community'];
			        $user->own_update = '0';

				    $e = $user->save(); //保存数据
				}
				$transaction->commit(); //结束事务
            }catch(Exception $e){
				print_r($e);
                $transaction->rollBack();
            }

            return $this->redirect(['/admin/user/index', 'name' => $post['name']]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
				'company' => $company,
				'role' => $role
            ]);
        }
    }

	//修改密码
	public function actionChange()
	{
		$model = new SysUser();

        return $this->render('form', [
            'model' => $model,
        ]);
	}

	//提交新密码
	public function actionP()
	{
		$session = Yii::$app->session;
		$id = $_SESSION['user']['0']['id'];
		//获取数据中的密码
		$pd = SysUser::find()->select('new_pd')->where(['id'=> $id])->asArray()->one();

		$password = $_POST['SysUser']['password'];// 传递过来的密码
		$new = $_POST['SysUser']['n'];//传递过来的新密码
		$p = md5($password); // 经过md5加密旧密码
		$n = md5($new); // 经过md5加密新密码

		if($pd['new_pd'] == $p){
			Sysuser::updateAll(['new_pd' => $n],'id = :id',[':id' => $id]);
			echo "<script>alert('密码修改成功！');parent.location.href='/site';</script>";
		}else{
			$session->setFlash('fail','1');
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
    /**
     * Updates an existing SysUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SysUser model.
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
     * Finds the SysUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SysUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SysUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
