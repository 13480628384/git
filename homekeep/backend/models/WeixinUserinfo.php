<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "weixin_userinfo".
 *
 * @property string $id 编号
 * @property string $app_id 应用编号
 * @property string $user_type 用户类型
 * @property string $openid 微信用户标示
 * @property double $consume_total_account 消费总额度
 * @property double $pay_total_account 支付总金额
 * @property double $total_account 余额
 * @property string $status 1:有效 0:无效
 * @property string $create_by 创建者
 * @property string $create_date 创建时间
 * @property string $update_by 更新者
 * @property string $update_date 更新时间
 * @property string $remarks 备注信息
 * @property string $del_flag 删除标记（0：正常；1：删除）
 * @property string $sex 1时是男性，值为2时是女性，值为0时是未知
 * @property string $nickname 昵称
 * @property string $headimgurl 头像
 * @property string $country 国家
 * @property string $city 城市
 * @property string $province 省份
 * @property string $type 1未绑定，2已注册绑定
 */
class WeixinUserinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_userinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_by', 'create_date', 'update_date'], 'required'],
            [[ 'total_account'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['headimgurl'], 'string'],
            [['id', 'openid', 'create_by', 'nickname', 'country', 'city', 'province'], 'string', 'max' => 64],
            [[ 'status', 'sex', 'type'], 'string', 'max' => 1],
            [['remarks'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'total_account' => '总金额',
            'status' => '状态',
            'create_by' => '创建者',
            'create_date' => '关注时间',
            'update_date' => '更新时间',
            'remarks' => '备注',
            'sex' => '性别',
            'nickname' => '昵称',
            'headimgurl' => '头像',
            'country' => '国家',
            'city' => '城市',
            'province' => '省份',
            'type' => '类型',
        ];
    }
}
