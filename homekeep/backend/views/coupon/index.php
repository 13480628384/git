<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '优惠券';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增优惠券', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute'=>'type',
              'value'=>function($model){
                return $model->type == '1' ? '折扣券':'抵价券';
              }
            ],
            'nums',
            'randoms',
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return $model->status == '1' ? '有效':'无效';
                }
            ],
            'term_time',
            //'range:ntext',
            //'create_by',
            'create_date',
            //'update_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
