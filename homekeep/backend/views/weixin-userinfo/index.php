<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\WeixinUserinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '微信用户信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weixin-userinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?/*= Html::a('添加新微信用户', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'phone',
            'nickname',
            [
                'attribute'=>'headimgurl',
                'format' => ['raw'],
                'value' => function($model){
                    return Html::img($model->headimgurl,['width'=>'50','height'=>'50']);
                }

            ],
            [
                'attribute' => 'create_date',
                'value'=>function($model){
                    return  $model->create_date;
                },
            ],
            'country',
            'city',
            'province',
            [
                'attribute'=>'type',
                'value'=>function($model){
                    return $model->type == '1'?'未绑定':'已绑定';
                },
                'filter'=>['1'=>'未绑定','0'=>'已绑定']
            ],
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return $model->status == '1'?'有效':'无效';
                },
                'filter' => ['1'=>'有效','0'=>'无效'],
            ],
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
                },
                'filter'=>['1'=>'男','2'=>'女','0'=>'未知']
            ],
            ['class' => 'yii\grid\ActionColumn', 'header' => '操作', 'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看']);
                    },
                ],
                'headerOptions' => ['width' => '100']
            ],
        ],
    ]); ?>
</div>
