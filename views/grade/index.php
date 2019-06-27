<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\searchs\GradeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Grades');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Grade'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'text-center'
        ],
        'headerRowOptions' => [ // 头标题无没效要覆盖他的th
	        'text-align' => 'center',
        ],
        'pager'=>[
            //'options'=>['class'=>'hidden']//关闭自带分页
            'options' => [
                'class' => 'pagination mypage'
            ],
            'firstPageCssClass' => 're',
           // 'activePageCssClass' => 'bg-info',
            'firstPageLabel'=>"首页",
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'未页',
        ],
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'year',
            'student',
            [
                'label' => '语文',
                'attribute' => 'china',
                'value' => function($model){
                    return $model['china'];
                },
            ],
	        [
		        'label' => '数学',
		        'attribute' => 'math',
		        'value' => function($model){
			        return $model['math'];
		        }
	        ],
	        [
		        'label' => '英语',
		        'attribute' => 'english',
		        'value' => function($model){
			        return $model['english'];
		        }
	        ],
	        [
		        'label' => '历史',
		        'attribute' => 'history',
		        'value' => function($model){
			        return $model['history'];
		        }
	        ],
	        [
		        'label' => '总分',
		        'attribute' => 'total',
		        'value' => function($model){
			        return $model['total'];
		        }
	        ],
	        [
		        'label' => '平均分',
		        'attribute' => 'avg',
		        'value' => function($model){
			        return $model['avg'];
		        }
	        ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php
$css = <<<EOP
th{text-align:center} 
.mypage .re a{color:red}
.mypage .active a{background-color:pink;}
EOP;
$this->registerCss($css);
?>