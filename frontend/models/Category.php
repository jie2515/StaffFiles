<?php

namespace frontend\models;

use Yii;
use valentinek\behaviors\ClosureTable;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 *
 * @property CategoryTree[] $categoryTrees
 * @property CategoryTree[] $categoryTrees0
 * @property Category[] $parents
 * @property Category[] $children
 */

class Category extends \common\models\Category
{
//    protected function findModel($id)
//    {
//        if (($model = Category::findOne($id)) !== null) {
//            return $model;
//        }
//
//        throw new NotFoundHttpException('The requested page does not exist.');
//    }
}