<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * AccountController implements the CRUD actions for UserAccount model.
 */
class TreeController extends Controller
{	
    public function actionIndex()
    {
        return $this->render('index');
    }
}
