<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Category;

/**
 * CategorySearch represents the model behind the search form of `frontend\models\Category`.
 */
class CategorySearch extends \common\models\CategorySearch
{
//    public function search($params)
//    {
//        $query = Category::find();
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => ['pageSize' => 10,],
//        ]);
//    }

    public function search($params)
    {
        $query = Category::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //分页大小
                'pageSize' => 30,
                //设置地址栏当前页数参数名
                'pageParam' => 'p',
                //设置地址栏分页大小参数名
                'pageSizeParam' => 'pageSize',
                ],

                //设置排序

                'sort' => [
                    //默认排序方式
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                    //参与排序的字段
                    'attributes' => [
                        'id', 'name'
                    ],
                ],

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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}