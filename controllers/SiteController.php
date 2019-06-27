<?php

namespace app\controllers;

use app\common\lwtable\Lwtable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Member;
use app\models\Config;

class SiteController extends Controller
{

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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $column = [
            'time:datetime:时间',
            [
                'field' => 'description',
                'title' => '告警标题',
                'sortable' => true,
                'formatter' => 'formatter_description',
                'valign' => 'middle',
                'width' => '17%',
            ],
            [
                'field' => 'name',
                'title' => '对象名称',
                'format' => 'raw',
                'sortable' => true,
                'width' => '10%',
                'valign' => 'middle',
                'enableTitle' => true,
            ],
        ];
        $talbe = new Lwtable();
        $data = $talbe->setColumns($column)->setLwTableOptions(['tableid' => 'problemtable'])
            ->setBootstrapTable(['method' => 'get', 'idField' => 'eventid', 'url' => 'index', 'showExport' => true])
            ->setToolbar([
                [
                    'icon' => 'glyphicon-book', 'data-lw-action' => 'gongdan_onclick', 'text' => '发送工单',
                ],
                [
                    'data-lw-action' => 'batch_ack_onclick', 'data-idfield' => 'hostid',
                    'icon' => 'glyphicon-edit', 'text' => '批量确认',
                ],
            ])
            ->render();
        // echo "<pre>";
        // //print_r(Yii::$app->getFormatter());
        // print_r($data);die;

        // $model = Member::findOne(1);

        // print_r($model);


        return $this->render('index');
    }
    


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
