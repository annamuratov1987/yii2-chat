<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\ChatMessage;

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
                //'only' => ['correct', 'incorrect'],
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
                    throw new \yii\web\HttpException(403, 'У вас нет доступа к этой операция');
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $chatMessage = new ChatMessage();
        if($chatMessage->load(\Yii::$app->request->post())){
            if($chatMessage->save()){
                Yii::$app->response->data = ['result' => 'ok'];
            }else{
                Yii::$app->response->data = [
                    'result' => 'error',
                    'message' => 'Ошибка во время создания сообщения'
                ];
            }
        }
        return Yii::$app->response->send();
    }

    public function actionIncorrect($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $chatMessage = ChatMessage::findOne($id);
        if ($chatMessage && $chatMessage->setIncorrect()){
            Yii::$app->response->data = ['result' => 'ok'];
        }else{
            Yii::$app->response->data = [
                'result' => 'error',
                'message' => 'Ошибка во время выполнения операция'
            ];
        }
        return Yii::$app->response->send();
    }

    public function actionCorrect($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $chatMessage = ChatMessage::findOne($id);
        if ($chatMessage && $chatMessage->setCorrect()){
            Yii::$app->response->data = ['result' => 'ok'];
        }else{
            Yii::$app->response->data = [
                'result' => 'error',
                'message' => 'Ошибка во время выполнения операция'
            ];
        }
        return Yii::$app->response->send();
    }
}
