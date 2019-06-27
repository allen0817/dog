<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$value = current($model->allModels);
$key = key($model->allModels);
$this->title = Yii::t('app', 'Update Config: {name}', [
    'name' => $key,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $key, 'url' => ['view', 'key' =>$key]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="member-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
