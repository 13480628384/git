<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kucha\ueditor\UEditor;
/* @var $this yii\web\View */
/* @var $model backend\models\Shop */

$this->title = '新添加商品';
$this->params['breadcrumbs'][] = ['label' => 'Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-create">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <?= $form->field($model, 'type')->label('服务类型')->dropDownList(
                    ['1'=>'养老助残','2'=>'保姆月嫂','3'=>'清洁保洁','4'=>'更多服务']
            ) ?>
            <?= $form->field($model, 'img')->label('图片')->widget('manks\FileInput',[]) ?>
            <?= $form->field($model, 'title')->label('商品标题')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'price')->label('价格')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'service')->label('服务明细')
                ->widget('kucha\ueditor\UEditor',[
                    'clientOptions' => [
                        'initialFrameHeight' => '200',
                        'initialFrameWidth' => '400',
                        'lang' =>'zh-cn', //中文为 zh-cn
                        'toolbars' => [
                            [
                                'fullscreen', 'source', 'undo', 'redo', '|',
                                'fontsize',
                                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                                'forecolor', 'backcolor', '|',
                                'lineheight', '|',
                                'indent', '|','simpleupload'
                            ],
                        ]
                    ]
                ]) ?>

            <?php $model->status ='1'; ?>
            <?= $form->field($model, 'status')->label('状态')->radioList(['1'=>'有效','0'=>'无效']) ?>
            <?php $model->online ='1'; ?>
            <?= $form->field($model, 'online')->label('上下架')->radioList(['1'=>'上架','0'=>'下架']) ?>
            <?= $form->field($model, 'remarks')->label('备注')->textarea(['autofocus' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('重置', ['class'=>'btn btn-primary','name' =>'submit-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
