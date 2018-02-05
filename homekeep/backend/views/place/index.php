<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '微信订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?/*= Html::a('Create Place', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'auth.source',
            [
                'attribute' => 'title',
                'value' => 'shop.title',
                'label' => '商品标题',
            ],
            [
                'attribute' => 'name',
                'value' => 'address.name',
                'label' => '收货人',
            ],
            [
                'attribute' => 'phone',
                'value' => 'address.phone',
                'label' => '电话',
            ],
            'price',
            [
                'attribute'=>'status',
                'value'=>function($model){
                    switch ($model->status){
                        case '0':
                            return '未付款';
                            break;
                        case '1':
                            return '成功下单';
                            break;
                        case '2':
                            return '待服务';
                            break;
                        case '3':
                            return '服务中';
                            break;
                        case '4':
                            return '服务完成';
                            break;
                        case '5':
                            return '交易关闭';
                            break;
                    }
                },
                'filter' => ['0'=>'未付款','1'=>'成功下单','2'=>'待服务','3'=>'服务中','4'=>'服务完成','5'=>'交易关闭'],
            ],
            'out_trade_no',
            'transaction_id',
            'servicetime',
            //'coupon_id',
            'create_date',
            //'update_date',
            ['class' => 'yii\grid\ActionColumn', 'header' => '操作', 'template' => '{view} {update}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看']);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '处理订单']);
                    },
                ],
                'headerOptions' => ['width' => '100']
            ],
        ],
    ]); ?>
</div>
