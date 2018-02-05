<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use \kucha\ueditor\UEditor;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model backend\models\Place */

$this->title = '处理订单';
$this->params['breadcrumbs'][] = ['label' => 'Places', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
if( $model->status == '5' || $model->status == 1) {
    echo '订单未付款或已关闭，不能操作';
}else{
?>
<div class="place-update">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->field($model, 'status')->label('订单进度状态修改')->dropDownList(
            ['0'=>'未付款','1'=>'成功下单','2'=>'待服务','3'=>'服务中','4'=>'服务完成','5'=>'交易关闭']
        ) ?>
        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class'=>'btn btn-primary','name' =>'submit-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php }?>