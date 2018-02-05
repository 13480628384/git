<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WeixinUserinfo;

/**
 * WeixinUserinfoSearch represents the model behind the search form of `backend\models\WeixinUserinfo`.
 */
class WeixinUserinfoSearch extends WeixinUserinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'openid','phone', 'status', 'sex', 'nickname', 'country', 'city', 'province','type'], 'safe'],
            [[ 'total_account'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WeixinUserinfo::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
           /* 'consume_total_account' => $this->consume_total_account,
            'pay_total_account' => $this->pay_total_account,*/
            'status' => $this->status,
            'sex' => $this->sex,
            'type' => $this->type
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province]);

        return $dataProvider;
    }
}
