<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;

class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['add-admin', 'remove-admin'],
                'rules' => [
                    [
                        'actions' => ['add-admin', 'remove-admin'],
                        'allow' => true,
                        'roles' => ['updateRbacRole'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add-admin' => ['post'],
                    'remove-admin' => ['post']
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
            ]
        ];
    }

    public function actionAddAdmin($id){
        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        $result = $auth->assign($admin, $id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($result){
            Yii::$app->response->data = [
                'result' => 'ok',
            ];
        }else{
            Yii::$app->response->data = [
                'result' => 'errror',
                'message' => 'Ошибка!'
            ];
        }
        return Yii::$app->response->send();
    }

    public function actionRemoveAdmin($id){

        $user = User::findOne($id);
        if ($user != null && $user->username != 'admin'){
            $auth = Yii::$app->authManager;
            $admin = $auth->getRole('admin');
            $isRevoke = $auth->revoke($admin, $id);

            Yii::$app->response->format = Response::FORMAT_JSON;
            if($isRevoke){
                Yii::$app->response->data = [
                    'result' => 'ok',
                ];
            }else{
                Yii::$app->response->data = [
                    'result' => 'error',
                    'message' => 'Ошибка!!'
                ];
            }
            return Yii::$app->response->send();
        }


    }
}
