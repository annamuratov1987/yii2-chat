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

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}