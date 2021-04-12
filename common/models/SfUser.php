<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "sf_user".
 *
 * @property string $userid
 * @property string $username
 * @property string $password
 * @property string $md5pw
 * @property int $active
 * @property int|null $user_level
 */
class SfUser extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sf_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
//            [['userid', 'username', 'password', 'md5pw'], 'required'],
//            [['active', 'user_level'], 'integer'],
            [['userid'], 'string', 'max' => 20],
//            [['username'], 'string', 'max' => 32],
//            [['password', 'md5pw'], 'string', 'max' => 40],
            [['userid'], 'unique'],
            [['parent'], 'string', 'max' => 20],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'md5pw' => 'Md5pw',
            'active' => 'Active',
            'user_level' => 'User Level',
            'parent' => "Parent",
        ];
    }

    /**
     * {@inheritdoc}
     * @return SfUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SfUserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     * 根据user_backend表的主键（id）获取用户
     */
    public static function findIdentity($id)
    {
        return static::findOne(['userid' => $id]);
    }

    public static function findByUsername($username)
    {
//        session_start();
        $_SESSION['username'] = $username;
//        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        return static::findOne(['userid' => $username, 'active' => self::STATUS_ACTIVE]);

    }

    /**
     * @inheritdoc
     * 根据access_token获取用户，我们暂时先不实现，我们在文章 http://www.manks.top/yii2-restful-api.html 有过实现，如果你感兴趣的话可以先看看
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'active' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * @inheritdoc
     * 用以标识 Yii::$app->user->id 的返回值
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * 获取auth_key
     */
    public function getAuthKey()
    {
//        return $this->auth_key;
        return $this->md5pw;
    }

    /**
     * @inheritdoc
     * 验证auth_key
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
//        $hash_password = Yii::$app->security->generatePasswordHash($password);
//        return Yii::$app->security->validatePassword($password, $this->password);
//        echo md5($password).'<br>';
//        echo $this->md5pw;
//        exit;
        if(is_null($this->md5pw)) return false;
        // return \Yii::$app->security->validatePassword($password, $this->password_hash);
        // return \Yii::$app->security->validatePassword($password, $this->passwd);
        if(md5($password)==$this->md5pw) return true;
    }


    public function setPassword($password)
    {
    //    $this->md5pw = Yii::$app->security->generatePasswordHash($password);
        $this->md5pw = md5($password);
        $this->password = substr(md5($password), 8, 16);
    }

    
    /**
     * 生成 "remember me" 认证key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();

    }

    /**
     * 生成accessToken字符串
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateAccessToken()
    {
        $this->access_token=Yii::$app->security->generateRandomString();
        return $this->access_token;
    }

        /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
