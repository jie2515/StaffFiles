<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SfUser]].
 *
 * @see SfUser
 */
class SfUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return SfUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SfUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
