<?php
namespace app\models;

use yii\base\Model;

class ChatMessageForm extends Model
{
    public $text;

    public function rules() {
        return [
            ['text', 'required', 'message' => 'Введите сообщение'],
        ];
    }
}