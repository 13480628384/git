<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WeixinUserinfo */

$this->title = 'Update Weixin Userinfo: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Weixin Userinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="weixin-userinfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
