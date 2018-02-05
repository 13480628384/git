<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property int $id
 * @property int $shop_id 商品id
 * @property int $address_id 下单地址
 * @property string $openid 用户openid
 * @property string $price 价格
 * @property string $status 0未付款 1成功下单 2 待服务 3服务中 4服务完成 5交易关闭
 * @property string $out_trade_no 商户下单号
 * @property string $ip 用户ip
 * @property string $transaction_id 支付订单号
 * @property string $servicetime 服务时间
 * @property int $coupon_id 优惠券id
 * @property string $remarks 下单备注
 * @property string $create_date 下单时间
 * @property string $update_date 更新下单时间
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }
    /**
     *  关联shop表
     */
    public function getShop()
    {
        // hasOne要求返回两个参数 第一个参数是关联表的类名 第二个参数是两张表的关联关系
        return $this->hasOne(\backend\models\Shop::className(), ['id' => 'shop_id']);
    }
    /**
     *  关联address表
     */
    public function getAddress()
    {
        // hasOne要求返回两个参数 第一个参数是关联表的类名 第二个参数是两张表的关联关系
        return $this->hasOne(\backend\models\Address::className(), ['id' => 'address_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'address_id', 'openid'], 'required'],
            [['shop_id', 'address_id', 'coupon_id'], 'integer'],
            [['price'], 'number'],
            [['servicetime', 'create_date', 'update_date'], 'safe'],
            [['openid', 'out_trade_no', 'transaction_id'], 'string', 'max' => 64],
            [['status'], 'string', 'max' => 1],
            [['ip'], 'string', 'max' => 15],
            [['remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => '商品id',
            'shop.title' => '商品标题',
            'address.name' => '姓名',
            'address.phone' => '电话',
            'address_id' => '地址id',
            'openid' => '用户id',
            'price' => '价格',
            'status' => '状态',
            'out_trade_no' => '商户订单号',
            'ip' => 'IP地址',
            'transaction_id' => '微信订单号',
            'servicetime' => '服务时间',
            'coupon_id' => '优惠券id',
            'remarks' => '备注',
            'create_date' => '下单时间',
            'update_date' => '更新时间',
        ];
    }
}
