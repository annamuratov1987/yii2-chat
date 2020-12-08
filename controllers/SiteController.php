<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use app\models\LoginForm;
use app\models\RegistrationForm;
use app\models\ChatMessage;
use app\models\ChatMessageForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'registration', 'logout', 'users', 'incorrect-messages'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'registration'],
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['users', 'incorrect-messages'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new \Exception('У вас нет доступа к этой странице');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $chatMessages = ChatMessage::find();
        if (!Yii::$app->user->identity || !Yii::$app->user->identity->isAdmin()){
            $chatMessages = $chatMessages->where(['status' => ChatMessage::STATUS_ACTIVE]);
        }
        $chatMessages = $chatMessages->orderBy('created_at DESC')->all();
        $chatForm = new ChatMessageForm();

        return $this->render('index', ['chatForm' => $chatForm, 'chatMessages' => $chatMessages]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegistration(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new RegistrationForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $user = new User();
            $user->firstname = $model->firstname;
            $user->lastname = $model->lastname;
            $user->username = $model->username;
            $user->password = \Yii::$app->security->generatePasswordHash($model->password);
            if($user->save()){
                return $this->redirect(Url::to(['site/login']));
            }
        }

        return $this->render('registration', compact('model'));
    }

    public function actionUsers(){
        $users = User::find()->where('id!=:id', [':id'=>Yii::$app->user->getId()])->all();
        return $this->render('users', ['users' => $users]);
    }

    public function actionIncorrectMessages(){
        $chatMessages = ChatMessage::find()->where('status=:status', [':status' => ChatMessage::STATUS_INCORRECT])->all();
        return $this->render('incorrect-messages', ['chatMessages' => $chatMessages]);
    }
}
