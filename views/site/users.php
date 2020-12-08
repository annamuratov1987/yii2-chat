<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Пользователи';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?if(!empty($users)):?>
        <ul class="list-group">
        <?foreach ($users as $user):?>
            <li class="list-group-item">
                <?= $user->isAdmin()? '<span class="badge">Админ</span>':' ' ?>
                <?= Html::encode($user->firstname).' '.Html::encode($user->lastname) ?>
                (<?= Html::encode($user->username) ?>)
                <?if(isset(Yii::$app->user->identity) && Yii::$app->user->identity->isAdmin()):?>
                    <? if(!$user->isAdmin()):?>
                        <button type="button" class="btn btn-success btn-xs btn-add-admin float-right" data-id="<?= $user->id?>">Сделать админ</button>
                    <?else:?>
                        <button type="button" class="btn btn-danger btn-xs btn-remove-admin float-right" data-id="<?= $user->id?>">Это не админ</button>
                    <?endif;?>
                <?endif;?>
            </li>
        <?endforeach;?>
    </ul>
    <?else:?>
    <div class="alert alert-warning" role="alert">Нет другие пользователи!</div>
    <?endif;?>
</div>
