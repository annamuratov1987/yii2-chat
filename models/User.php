<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_REGISTER = 'register';

    public $confirmPassword;

    public static function tableName()
    {
        return '{{user}}';
    }

    public function rules() {
        return [
            [['username', 'password'], 'required', 'message' => 'Заполните поле'],
            [['firstname', 'lastname', 'confirmPassword'], 'required', 'on' => self::SCENARIO_REGISTER, 'message' => 'Заполните поле'],
            ['username', 'unique', 'on' => self::SCENARIO_REGISTER, 'message' => 'Этот логин уже занят'],
            ['confirmPassword', 'passwordConfirmed', 'on' => self::SCENARIO_REGISTER],
        ];
    }

    public function attributeLabels() {
        return [
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'username' => 'Логин',
            'password' => 'Пароль',
            'confirmPassword' => 'Повторите пароль'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert){
            $this->password = \Yii::$app->security->generatePasswordHash($this->password);
        }

        return true;
    }

    public function passwordConfirmed($attribute, $params){
        if (!$this->hasErrors()){
            if ($this->password != $this->confirmPassword){
                $this->addError($attribute, 'Неправильно введен повтор пароля.');
            }
        }
    }

    public function isAdmin(){
        return \Yii::$app->authManager->checkAccess($this->id, 'admin');
    }
}
