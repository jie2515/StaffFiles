<?php

namespace frontend\models;

/**
 * This is the model class for table "category_tree".
 *
 * @property int $parent
 * @property int $child
 * @property int $depth
 *
 * @property Category $child0
 * @property Category $parent0
 */
class CategoryTree extends \common\models\CategoryTree
{
    public static function showParent($id)
    {
        return static::findOne(['parent' => $id]);
    }
}