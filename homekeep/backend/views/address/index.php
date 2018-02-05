<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '家庭住址';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?/*= Html::a('Create Address', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'phone',
            'details',
            'servicearea',
            //'is_default',
            //'create_by',
            'create_date',
            //'update_date',
            ['class' => 'yii\grid\ActionColumn', 'header' => '操作', 'template' => '{view}',
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
