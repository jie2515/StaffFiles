<?php

namespace frontend\models;

/**
 * This is the model class for table "tbl_user".
 *
 * @property string $userid
 * @property string $password
 * @property string $md5pw
 * @property int $user_level
 * @property string $username
 * @property string|null $email
 * @property string $location
 * @property int $active
 * @property int|null $user_right
 * @property string|null $last_pw_change
 * @property string|null $last_md5pw_change
 */
class TblUser extends \common\models\TblUser
{

}
