<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Place;

/**
 * PlaceSearch represents the model behind the search form of `\backend\models\Place`.
 */
class PlaceSearch extends Place
{
    /**
     * @inheritdoc
     */
    public  $title;
    public  $name;
    public  $phone;
    public function rules()
    {
        return [
            [['id', 'shop_id', 'address_id', 'coupon_id'], 'integer'],
            [['title','name','phone'],'safe'],
            [['openid', 'status', 'out_trade_no', 'ip', 'transaction_id', 'servicetime', 'remarks', 'create_date', 'update_date'], 'safe'],
            [['price'], 'number'],
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
        $query = Place::find();
        $query->joinWith(['shop']);
        $query->joinWith(['address']);
       // $query->orderBy(['place.status'=>'desc']);
        $query->select("place.*,shop.title,address.name,address.phone");
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
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'address_id' => $this->address_id,
            'place.price' => $this->price,
            'servicetime' => $this->servicetime,
            'coupon_id' => $this->coupon_id,
            'place.create_date' => $this->create_date,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'place.status', $this->status])
            ->andFilterWhere(['like', 'out_trade_no', $this->out_trade_no])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
            ->andFilterWhere(['like', 'shop.title', $this->title])
            ->andFilterWhere(['like', 'address.name', $this->name])
            ->andFilterWhere(['like', 'address.phone', $this->phone]);
        return $dataProvider;
    }
}
