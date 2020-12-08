<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\ChatMessage;

$this->title = 'Yii Чат';
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <div class="col-xs-12 col-sm-4 chat-form-content">
                <?php $form = ActiveForm::begin(['id' => 'chat-form', 'action' => Url::to(['chat-message/create'])]) ?>
                <?= $form->field($chatForm, 'text')->textarea()->label('')?>
                <div class="form-group">
                    <div>
                        <?= Html::submitButton('Отправит', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-xs-12 col-sm-8 chat-content">
                <? foreach($chatMessages as $chatMessage):?>
                    <div class="panel panel-<?= $chatMessage->status == ChatMessage::STATUS_INCORRECT ?'warning':($chatMessage->user->isAdmin()? 'success':'default')?>">
                        <div class="panel-heading">
                            <?= $chatMessage->user->isAdmin()? '<span class="badge">Админ</span>':' ' ?>
                            <?= Html::encode($chatMessage->user->firstname).' '.Html::encode($chatMessage->user->lastname) ?>
                            (<?= Html::encode($chatMessage->user->username)?>)
                            <span class="label label-default float-right"><?= $chatMessage->created_at ?></span>
                            <?if(isset(Yii::$app->user->identity) && Yii::$app->user->identity->isAdmin()):?>
                                <? if($chatMessage->status == \app\models\ChatMessage::STATUS_INCORRECT):?>
                                    <button type="button" class="btn btn-success btn-xs btn-correct float-right" data-id="<?= $chatMessage->id?>">Сделать корректный</button>
                                <?else:?>
                                    <button type="button" class="btn btn-danger btn-xs btn-incorrect float-right" data-id="<?= $chatMessage->id?>">Сделать некорректный</button>
                                <?endif;?>
                            <?endif;?>
                        </div>
                        <div class="panel-body">
                            <?= Html::encode($chatMessage->text)?>
                        </div>
                    </div>
                <?endforeach;?>
            </div>

        </div>

    </div>
</div>