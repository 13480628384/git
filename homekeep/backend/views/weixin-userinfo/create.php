<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WeixinUserinfo */

$this->title = 'Create Weixin Userinfo';
$this->params['breadcrumbs'][] = ['label' => 'Weixin Userinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weixin-userinfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
