<?php

namespace backend\controllers;

use Yii;
use app\models\Cos;
class TestController extends \yii\web\Controller
{
	public $layout = 'zeus';
    public function actionDispute(){
		if(Yii::$app->request->post()){
			
		}
		return $this->render('dispute',[
			
		]);
	}
	
	public function actionNewpeople(){
		if(Yii::$app->request->post()){
			
		}
		return $this->render('newpeople',[
			
		]);
	}
    
    public function actionTest(){
        $res = new Cos();
        $res->showList("/filename/");
        
    }
	
}
