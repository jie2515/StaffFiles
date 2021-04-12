<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TblUser;

/**
 * TblUserSearch represents the model behind the search form of `backend\models\TblUser`.
 */
class TblUserSearch extends TblUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'password', 'md5pw', 'username', 'email', 'location', 'last_pw_change', 'last_md5pw_change'], 'safe'],
            [['user_level', 'active', 'user_right'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = TblUser::find();

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
            'user_level' => $this->user_level,
            'active' => $this->active,
            'user_right' => $this->user_right,
            'last_pw_change' => $this->last_pw_change,
            'last_md5pw_change' => $this->last_md5pw_change,
        ]);

        $query->andFilterWhere(['like', 'userid', $this->userid])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'md5pw', $this->md5pw])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }
}
