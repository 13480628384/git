<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property int $id
 * @property string $type 1 折扣券 2 抵价券
 * @property string $nums 金额或者百分比
 * @property string $randoms 优惠随机码
 * @property string $status 0无效 1 有效
 * @property string $term_time 有效期
 * @property string $range 适用范围(商品id)
 * @property string $create_by 创建者
 * @property string $create_date 创建时间
 * @property string $update_date 更新时间
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term_time', 'range', 'randoms', 'nums','create_date', 'update_date'], 'required'],
            [['term_time', 'create_date', 'update_date'], 'safe'],
            //[['range'], 'string'],
            [['type'], 'string', 'max' => 11],
            [['nums'], 'string', 'max' => 4],
            [['nums'], 'number'],
            [['randoms'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 1],
            //[['create_by'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '优惠券类型',
            'nums' => '金额',
            'randoms' => '优惠随机码',
            'status' => '状态',
            'term_time' => '有效期',
            'range' => '适用范围',
            'create_by' => '创建者',
            'create_date' => '创建时间',
            'update_date' => '更新时间',
        ];
    }
}
