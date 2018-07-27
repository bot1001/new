<?php

namespace backend\controllers;

use app\models\Sms;
use app\models\SmsLog;
use Yii;
use common\models\SmsClient;
use app\models\SmsClientSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmsClientController implements the CRUD actions for SmsClient model.
 */
class SmsClientController extends Controller
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

    /**
     * Lists all SmsClient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmsClient model.
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
     * Creates a new SmsClient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SmsClient();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SmsClient model.
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
     * Deletes an existing SmsClient model.
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

    function actionSend()
    {
        $sms = Sms::Sms();
        return $this->render('send', ['sms' => $sms]);
    }

    //手动发送月缴费短信
    function actionSend01($sms, $name)
    {
        $model = new SmsClient();
        $guest = '裕达物业'; //客户

        if ($model->load(Yii::$app->request->post()))
        {
            $post = $_POST['SmsClient'];
            $realestate = $post['room']; //房号ID
            $result = SmsClient::send($realestate); //查询需要发送的数据

            $result = Json::decode($result); //转换数组

            $address = $result['name'];
            $old = $result['old'];
            $now = $result['now'];
            $amount = $result['amount'];
            $cellphone = $result['phone']; //物业服务中心联系方式
            $phone = $post['phone']; //接收手机号码

            $SmsParam = "{name:'$address',now:'$now',old:'$old','amount': $amount, phone: $cellphone,guest:'$guest'}"; //组合短信信息
            $r = \common\models\Sms::Send($name, $phone, $sms, $SmsParam); //调用发送短信类

            $success = '0'; //默认发送成功条数为0
            $fail = '0'; //默认发送失败条数为0
            if($r == '1')
            {
                $success ++;
            }else{
                $fail ++;
            }

            $user = reset($_SESSION['user']);

            $log = new SmsLog();

            $log->sign_name = $name;
            $log->sms = $sms;
            $log->type = '1';
            $log->sender = $user['id'];
            $log->count = $fail+$success;
            $log->success = $success;
            $log->sms_time = time();
            $log->property = '月度缴费单';

            $log->save(); //保存发送记录

            $session = Yii::$app->session;
            $session->setFlash('result', "成功发送 $success 条，失败 $fail 条");

            return $this->redirect(['send01','sms' => $sms, 'name' => $name]);
        }

        return $this->render('form', ['model' => $model, 'guest' => $guest]);
    }

    //短信预览
    function actionMessage()
    {
        $get = $_GET['SmsClient'];

        $realestate = $get['room']; //房号ID
        $result = SmsClient::send($realestate);

        return $result;
    }

    /**
     * Finds the SmsClient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SmsClient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SmsClient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
