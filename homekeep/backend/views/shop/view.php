<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Shop */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'type',
            [
                'attribute'=>'img',
                'format' => ['raw'],
                'value' => function($model){
                    return Html::img($model->img,['width'=>'100','height'=>'100']);
                }

            ],
            'title',
            'samlltitle',
            'price',
            'service:ntext',
            'status',
            'online',
            'remarks',
            'create_by',
            'create_date',
            'update_date',
        ],
    ]) ?>

</div>
