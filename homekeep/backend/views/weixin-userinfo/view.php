<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WeixinUserinfo */

$this->title = $model->nickname;
$this->params['breadcrumbs'][] = ['label' => 'Weixin Userinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weixin-userinfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'openid',
            'total_account',
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return $model->status == '1'?'有效':'无效';
                }
            ],
            'create_by',
            'create_date',
            'update_date',
            'remarks',
            [
                'attribute'=> 'sex',
                'value' => function($model){
                    if($model->sex == '1'){
                        return '男';
                    }else if($model->sex == '2'){
                        return '女';
                    }else{
                        return '未知';
                    }
                }
            ],
            'nickname',
            [
                'attribute'=>'headimgurl',
                'format' => ['raw'],
                'value' => function($model){
                    return Html::img($model->headimgurl,['width'=>'150','height'=>'150']);
                }

            ],
            'country',
            'city',
            'province',
            [
                'attribute'=>'type',
                'value'=>function($model){
                    return $model->type == '1'?'未绑定':'已绑定';
                }
            ],
        ],
    ]) ?>

</div>
