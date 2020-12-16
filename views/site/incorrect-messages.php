<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Некорректные сообщения';
?>
<div class="site-index">
    <div class="body-content">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-xs-12">
                <?if(!empty($chatMessages)):?>
                    <?foreach ($chatMessages as $chatMessage):?>
                        <div class="panel panel-<?= ($chatMessage->status == \app\models\ChatMessage::STATUS_INCORRECT)?'warning':($chatMessage->user->isAdmin()? 'success':'default')?>">
                            <div class="panel-heading">
                                <?= $chatMessage->user->isAdmin()? '<span class="badge">Админ</span>':' ' ?>
                                <?= Html::encode($chatMessage->user->firstname).' '.Html::encode($chatMessage->user->lastname) ?>
                                (<?= Html::encode($chatMessage->user->username)?>)
                                <span class="label label-default float-right"><?= date("Y-m-d H:i:s", $chatMessage->created_at) ?></span>
                                <?if(isset(Yii::$app->user->identity) && Yii::$app->user->identity->isAdmin()):?>
                                    <button type="button" class="btn btn-success btn-xs btn-correct float-right" data-id="<?= $chatMessage->id?>">Сделать корректный</button>
                                <?endif;?>
                            </div>
                            <div class="panel-body">
                                <?= Html::encode($chatMessage->text)?>
                            </div>
                        </div>
                    <?endforeach;?>
                <?else:?>
                    <div class="alert alert-warning" role="alert">Некорректные сообщения не найдено!</div>
                <?endif;?>
            </div>
        </div>

    </div>
</div>