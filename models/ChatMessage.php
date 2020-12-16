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

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    static::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    public function rules() {
        return [
            ['text', 'required', 'message' => 'Нет текст сообщения'],
            ['text', 'string'],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert){
            $this->user_id = \Yii::$app->user->getId();
        }

        return true;
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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