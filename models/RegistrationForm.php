<?php
namespace app\models;

use yii\base\Model;
use app\models\User;

class RegistrationForm extends Model
{
    public $firstname;
    public $lastname;
    public $username;
    public $password;
    public $confirmPassword;

    public function rules() {
        return [
            [['firstname', 'lastname', 'username', 'password', 'confirmPassword'], 'required', 'message' => 'Заполните поле'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят'],
            ['confirmPassword', 'passwordConfirmed'],
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

    public function passwordConfirmed($attribute, $params){
        if (!$this->hasErrors()){
            if ($this->password != $this->confirmPassword){
                $this->addError($attribute, 'Неправильно введен повтор пароля.');
            }
        }
    }
}