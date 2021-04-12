<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_group".
 *
 * @property int $ID
 * @property string $User_ID 用户ID
 * @property string $Group_ID 权限组ID
 * @property string|null $Creator 创建人
 * @property string|null $Create_Time 创建时间
 * @property string|null $Modifier 修改人
 * @property string|null $Modify_Time 修改时间
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['User_ID', 'Group_ID'], 'required'],
            [['Create_Time', 'Modify_Time'], 'safe'],
            [['User_ID'], 'string', 'max' => 20],
            [['Group_ID', 'Creator', 'Modifier'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'User_ID' => 'User ID',
            'Group_ID' => 'Group ID',
            'Creator' => 'Creator',
            'Create_Time' => 'Create Time',
            'Modifier' => 'Modifier',
            'Modify_Time' => 'Modify Time',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserGroupQuery(get_called_class());
    }
}
