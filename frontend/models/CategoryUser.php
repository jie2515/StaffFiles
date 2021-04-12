<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "category_user".
 *
 * @property string $userid
 * @property int $category_id
 * @property int $ischecked
 *
 * @property Category $category
 * @property SfUser $user
 */
class CategoryUser extends \common\models\CategoryUser
{
    public function findByUserid($userid)
    {
//        return static::findAll(array(
////            'userid' => $userid,
//            'condition' => 'userid='.$userid,
//            'select' =>array('category_id','is_checked'),
//        ));
        return static::find()
            ->select(['category_id','is_checked'])
            ->where(['userid' => $userid])
            ->all();
    }

}