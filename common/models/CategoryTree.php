<?php

namespace common\models;

use Yii;

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
class CategoryTree extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sf_category_tree';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child', 'depth'], 'integer'],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['child' => 'id']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
            'depth' => 'Depth',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(Category::className(), ['id' => 'child']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent']);
    }

    /**
     * {@inheritdoc}
     * @return CategoryTreeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryTreeQuery(get_called_class());
    }
}
