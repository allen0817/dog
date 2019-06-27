<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Member */


$value = current($model->allModels);
$key = key($model->allModels);
$this->title = $key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'key' => $key], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'key' => $key], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'KEY',
                'value' => function($model, $widget){
                    return key($model->allModels);
                }
            ],
            [
                'label' => 'VALUE',
                'value' => function($model,$widget){
                    return current($model->allModels);
                }
            ],
            [
                'label' => 'TYPE',
                'value' => function($model,$widget){
                    return gettype( current($model->allModels) );
                }
            ],

        ],
    ]) ?>

</div>
