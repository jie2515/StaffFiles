<?php

namespace frontend\models;

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
class UserGroup extends \common\models\UserGroup
{

}
