<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;
/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property string $type 服务类型（养老助残 保姆月嫂 清洁保洁  更多服务）
 * @property string $parent_id 上级服务类型id
 * @property string $img 商品图片
 * @property string $title 商品标题
 * @property string $samlltitle 商品小标题
 * @property string $price 商品价格
 * @property string $service 服务明细
 * @property string $status 1有效 0无效
 * @property string $online 上下架（0下架 1上架）
 * @property string $remarks 服务备注
 * @property string $create_by 创建者
 * @property string $create_date 添加时间
 * @property string $update_date 更新时间
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['img'], 'required'],
            [['price'], 'number'],
            [['price'], 'required'],
            [['service'], 'string'],
            [['service'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['type', 'status', 'online'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '服务类型',
            'parent_id' => '上级服务类型id',
            'img' => '图片',
            'title' => '标题',
            'samlltitle' => '小标题',
            'price' => '价格',
            'service' => '服务明细',
            'status' => '状态',
            'online' => '上下架',
            'remarks' => '服务备注',
            'create_by' => '创建者',
            'create_date' => '创建时间',
            'update_date' => '更新时间',
        ];
    }
}
