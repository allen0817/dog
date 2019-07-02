<?php
namespace app\controllers;

use app\common\myEvent;
use app\models\Config;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;


class ConfigController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){
    	$conf = new Config();
        $data = $conf->getData();

        return $this->render('index',[
            'model' => $data,
        ]);

    }

    public function actionView($key){
        $model = new Config();
        return $this->render('view', [
            'model' => $model->getData($key),
        ]);
    }
    public function actionUpdate($key)
    {
        $conf = new Config();
        $model = $conf->getData($key);
        if (Yii::$app->request->post() && $conf->save(Yii::$app->request->post()) ) {
            return $this->redirect(['view', 'key' => $key]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public  function  actionDelete($key){
        $conf = new Config();
        $conf->delete($key);
        return $this->redirect(['index']);
    }

    public function actionCreate(){
        $conf = new Config();
        if(Yii::$app->request->post() && $conf->save(Yii::$app->request->post()) ){
        	//触发事件

            return $this->redirect(['index']);
        }
        return $this->render('create');
    }

}
