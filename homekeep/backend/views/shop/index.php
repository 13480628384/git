<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新添加商品', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'type',
                'value'=>function($model){
                    if($model->type == '1'){
                        return '养老助残';
                    }elseif($model->type == '2'){
                        return '保姆月嫂';
                    }elseif($model->type == '3'){
                        return '清洁保洁';
                    }elseif($model->type == '4'){
                        return '更多服务';
                    }
                },
                'filter' => ['1'=>'养老助残','2'=>'保姆月嫂','3'=>'清洁保洁','4'=>'更多服务'],
            ],
            [
                'attribute'=>'img',
                'format' => ['raw'],
                'value' => function($model){
                    return Html::img(dirname(dirname('http://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'])).'/'.$model->img,['width'=>'100','height'=>'100']);
                }

            ],
            'title',
            //'samlltitle',
            [
                    'attribute'=>'price',
                'headerOptions' => ['width' => '10'],
            ],
            //'service:ntext',
            [
                    'attribute'=>'status',
                'value'=>function($model){
                    return $model->status == '1'?'有效':'无效';
                },
                'filter'=>['1'=>'有效','0'=>'无效']
            ],
            [
                'attribute'=>'online',
                'value'=>function($model){
                    return $model->online == '1'?'上架':'下架';
                },
                'filter'=>['1'=>'上架','0'=>'下架']
            ],
            //'remarks',
            //'create_by',
            [
                'attribute' => 'create_date',
                'headerOptions' => ['width' => '12%'],
            ],
            //'updte_date',

            ['class' => 'yii\grid\ActionColumn', 'header' => '操作', 'template' => '{view} {delete}{update}',
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
