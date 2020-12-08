<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\ChatMessage;
use app\models\ChatMessageForm;

class ChatMessageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['correct', 'incorrect'],
                        'allow' => true,
                        'roles' => ['updateChatMessage'],
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    if ($action->id == 'create'){
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = [
                            'result' => 'error',
                            'message' => 'Для отправка сообщение нужен зарегистрироваться.'
                        ];
                        return Yii::$app->response->send();
                    }

                    throw new \Exception('У вас нет доступа к этой странице');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'correct' => ['post'],
                    'incorrect' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionCreate()
    {
        $chatForm = new ChatMessageForm();
        if($chatForm->load(\Yii::$app->request->post()) && $chatForm->validate()){
            $chatMessage = new ChatMessage();
            $chatMessage->text = $chatForm->text;
            $chatMessage->user_id = \Yii::$app->user->getId();
            $chatMessage->created_at = date("Y-m-d H:i:s", time());
            if($chatMessage->save()){
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = ['result' => 'ok'];
                return Yii::$app->response->send();
            }
        }
    }

    public function actionIncorrect($id){
        $chatMessage = ChatMessage::findOne($id);
        if ($chatMessage){
            $chatMessage->status = ChatMessage::STATUS_INCORRECT;
            if ($chatMessage->save()){
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = ['result' => 'ok'];
                return Yii::$app->response->send();
            }
        }
    }

    public function actionCorrect($id){
        $chatMessage = ChatMessage::findOne($id);
        if ($chatMessage){
            $chatMessage->status = ChatMessage::STATUS_ACTIVE;
            if ($chatMessage->save()){
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = ['result' => 'ok'];
                return Yii::$app->response->send();
            }
        }
    }
}
