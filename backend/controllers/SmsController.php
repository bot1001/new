<?php

namespace backend\controllers;

use Yii;
use app\models\Sms;
use app\models\SmsSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;

/**
 * SmsController implements the CRUD actions for Sms model.
 */
class SmsController extends Controller
{
    /**
     * {@inheritdoc}
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

    //GridView页面直接编辑
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'sms' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Sms::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                },
                'ajaxOnly' => true,
            ]
        ]);
    }

    /**
     * Lists all Sms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsSearch();

        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
            $searchModel->id = $id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //发送验证码
    function actionSend($time, $phone)
    {
        //判断获取验证码时间
        if(!empty($time)){
            $t = strtotime('now'); //当前时间戳
            $t01 = $t-$time;

            if($t01 < 10){
                return '2';
            }
        }

        $name = '裕家人'; //应用名称
        $sms = 'SMS_23890023'; //模板编号

        //随机获取六位数验证码
        $code = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        //短信发送
        $SmsParam = "{code:'$code'}"; //组合短信信息
//        $r = \common\models\Sms::Send($name, $phone, $sms, $SmsParam); //调用发送短信类
//
//        if($r == '1') //发送成功返回验证码
//        {
            $code = ['code' => $code, 'timeStamp' => date(time())]; //组合返回信息
            $code = Json::encode($code);
            return $code;
//        }

        return false;
    }

    /**
     * Displays a single Sms model.
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
     * Creates a new Sms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sms model.
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
     * Finds the Sms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sms::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
