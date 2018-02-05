<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property string $name 联系人
 * @property string $phone 联系电话
 * @property string $details 详细地址
 * @property string $servicearea 服务面积
 * @property string $is_default 默认地址 0不默认 1默认
 * @property string $create_by 创建者
 * @property string $create_date 创建时间
 * @property string $update_date 更新时间
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'create_by', 'create_date', 'update_date'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['name', 'create_by'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
            [['details'], 'string', 'max' => 255],
            [['servicearea'], 'string', 'max' => 3],
            [['is_default'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'phone' => '电话',
            'details' => '详细地址',
            'servicearea' => '服务面积',
            'is_default' => '是否默认',
            'create_by' => '创建者',
            'create_date' => '创建时间',
            'update_date' => '更新时间',
        ];
    }
}
