<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\searchs\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Subjects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Subject'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
//            'status',
//            'create_at',
            [
	            'label'=>'状态',
                'attribute' => 'status',
                'value' => function($model){
                    return \app\models\Subject::getStatus( $model->status );
                }
            ],

	        [
		        'label' => '增加时间',
		        'attribute' => 'create_at',
		        //'format' => 'datetime'
		        'value' => function($model){
			        return  date('Y-m-d H:i:s',$model->create_at);
		        }
	        ],
	        [
	            'label' => '修改时间',
                'attribute' => 'update_at',
                //'format' => 'datetime'
		        'value' => function($model){
                    return  date('Y-m-d H:i:s',$model->update_at);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
