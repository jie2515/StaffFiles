<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[TblUser]].
 *
 * @see TblUser
 */
class TblUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TblUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TblUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
