<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Member */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Config');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Config'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $model,
//        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'KEY',
                'value' => function($model, $key, $index, $grid){
                   return $key;
                }
            ],
            [
                'label' => 'VALUE',
                'value' => function($model,$key,$index,$grid){
                    return $model;
                }
            ],
            [
                'headerOptions'=>[ 'class' => 'text-center'],
                'contentOptions'=>[ 'class' => 'text-center'],
                'header' => '操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            ['view', 'key' => $key]
                        );
                    },
                    'update' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            ['update','key'=>$key]
                        );
                    },
                    'delete' => function($url,$model,$key){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            ['delete','key'=>$key],
                            [
                                    'data' => [
                                            'confirm' => '你确定要删除: ' . $model
                                    ]
                            ]
                        );
                    }


                ]
            ],
        ],

    ]);?>
    <?php Pjax::end(); ?>
</div>

<?php $js = <<<EOP
    $(function(){
        alert(111)
    })
EOP;
//$this->registerJs($js);

?>

<?php

if($this->beginCache('config',['duration'=>300])){

    echo $this->render('test');

    $this->endCache();
}


?>



<script type="text/javascript">
    <?php $this->beginBlock('config') ?>
        $(function () {
            //alert('hello allen')
        })

    <?php $this->endBlock(); ?>
</script>
<?php $this->registerJs($this->blocks['config'],3);  ?>