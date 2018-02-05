<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use dosamigos\datepicker\DatePicker;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Coupon */

$this->title = '更新优惠券';
$this->params['breadcrumbs'][] = ['label' => 'Coupons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coupon-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="coupon-create">
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id'=>'form']); ?>
                <?= $form->field($model, 'type')->label('优惠类型')->dropDownList(
                    ['1'=>'折扣券','2'=>'抵价券']
                ) ?>
                <?= $form->field($model, 'nums')->label('金额')->textInput() ?>
                <?= $form->field($model, 'randoms')->label('优惠随机码',['class'=>'random'])->textInput(['readonly' => true]) ?>
                <?= Html::button('生成随机码', ['class' => 'teaser']) ?>
                <?= $form->field($model, 'term_time')->label('有效期')
                    ->widget(
                        DatePicker::classname(), [
                            'options' => ['placeholder' => ''],
                            'value' => '2016-05-03 22:10:10',
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'format' => 'yyyy-mm-dd',
                                'startDate' =>date('Y-m-d',strtotime("+1 day")),
                            ]
                        ]
                    );?>
                <?= $form->field($model, 'range')->label('适用范围')->
                checkboxList(
                    ArrayHelper::map($shop_id,'id', 'title'),
                    ['item'=>function($index, $label, $name, $checked, $value){
                        $checkStr = $checked?"checked":"";
                        return '<label style="font-size: 20px;"><input style="width:30px;height: 30px;" type="checkbox" name="'.$name.'" value="'.$value.'" '
                            .$checkStr.' class="class'.$index.'" data-uid="user'.$index.'">'.$label.'</label>';
                    }]
                )?>
                <?php $model->status ='1'; ?>
                <?= $form->field($model, 'status')->label('状态')->radioList(['1'=>'有效','0'=>'无效']) ?>
                <div class="form-group">
                    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('重置', ['class'=>'btn btn-primary','name' =>'submit-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('create') ?>
$(function($) {
function GetRandomNum(Min,Max)
{
var Range = Max - Min;
var Rand = Math.random();
return(Min + Math.round(Rand * Range));
}
var  teaser = $('.teaser');
teaser.click (function(){
$('#coupon-randoms').val(GetRandomNum(1,10000000));
})
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['create'], \yii\web\View::POS_END); ?>
<script>

</script>
