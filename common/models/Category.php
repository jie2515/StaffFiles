<?php

namespace common\models;

use Yii;
use valentinek\behaviors\ClosureTable;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 *
 * @property CategoryTree[] $categoryTrees
 * @property CategoryTree[] $categoryTrees0
 * @property Category[] $parents
 * @property Category[] $children
 */
class Category extends \yii\db\ActiveRecord
{
    public $leaf;

    public function behaviors() {
        return [
            [
                'class' => ClosureTable::className(),
                'tableName' => 'sf_category_tree'
            ],
        ];
    }

    public static function find()
    {
        return new CategoryQuery(static::className());
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sf_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'trim'],
            [['name'], 'default'],
            [['url'], 'string', 'max' => 2083],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => "Url"
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryTrees()
    {
        return $this->hasMany(CategoryTree::className(), ['child' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryTrees0()
    {
        return $this->hasMany(CategoryTree::className(), ['parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(Category::className(), ['id' => 'parent'])->viaTable('category_tree', ['child' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['id' => 'child'])->viaTable('category_tree', ['parent' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CategoryQuery the active query used by this AR class.
     */
//    public static function find()
//    {
//        return new CategoryQuery(get_called_class());
//    }
}
