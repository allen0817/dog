<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$this->title = Yii::t('app', 'Create Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('create_form', [
       // 'model' => $model,
    ]) ?>

</div>
