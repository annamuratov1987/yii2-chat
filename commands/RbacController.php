<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // добавляем разрешение "updateChatMessage"
        $updateChatMessage = $auth->createPermission('updateChatMessage');
        $updateChatMessage->description = 'Изменить сообщение';
        $auth->add($updateChatMessage);

        // добавляем разрешение "updateRbacRole"
        $updateRbacRole = $auth->createPermission('updateRbacRole');
        $updateRbacRole->description = 'Изменить права пользователя';
        $auth->add($updateRbacRole);

        // добавляем роль "admin" и даём роли разрешении
        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);
        $auth->addChild($admin, $updateChatMessage);
        $auth->addChild($admin, $updateRbacRole);

        return ExitCode::OK;
    }

    public function actionSetAdmin(){
        $user = User::findByUsername('admin');
        if ($user == null){
            $user = new User();
            $user->firstname = 'Администратор';
            $user->lastname = '';
            $user->username = 'admin';
            $user->password = \Yii::$app->security->generatePasswordHash('admin');
            if(!$user->save()){
                return ExitCode::UNSPECIFIED_ERROR;
            }
        }

        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        if ($auth->assign($admin, $user->getId())){
            return ExitCode::OK;
        }else{
            return ExitCode::UNSPECIFIED_ERROR;
        }

    }

}