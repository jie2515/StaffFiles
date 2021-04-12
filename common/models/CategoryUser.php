<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category_user".
 *
 * @property int $id
 * @property string $userid
 * @property int $category_id
 * @property int $checked
 *
 * @property Category $category
 * @property SfUser $user
 */
class CategoryUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sf_category_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'category_id'], 'required'],
            [['category_id', 'checked'], 'integer'],
            [['userid'], 'string', 'max' => 20],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => SfUser::className(), 'targetAttribute' => ['userid' => 'userid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'category_id' => 'Category ID',
            'checked' => 'Checked',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SfUser::className(), ['userid' => 'userid']);
    }

    /**
     * {@inheritdoc}
     * @return CategoryUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryUserQuery(get_called_class());
    }
}
