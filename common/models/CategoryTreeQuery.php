<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CategoryTree]].
 *
 * @see CategoryTree
 */
class CategoryTreeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CategoryTree[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CategoryTree|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
