<?php
namespace app\models;


class ChatMessage extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = null;
    const STATUS_INCORRECT = 1;

    public static function tableName()
    {
        return '{{chat_message}}';
    }

    public function rules() {
        return [
            ['text', 'required', 'message' => 'Нет текст сообщения'],
            ['user_id', 'required', 'message' => 'Вы не авторизованный пользователь'],
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function create(){
        $this->user_id = \Yii::$app->user->getId();
        $this->created_at = date("Y-m-d H:i:s", time());
        return $this->save();
    }

    public function setIncorrect(){
        $this->status = self::STATUS_INCORRECT;
        return $this->save();
    }

    public function setCorrect(){
        $this->status = self::STATUS_ACTIVE;
        return $this->save();
    }
}