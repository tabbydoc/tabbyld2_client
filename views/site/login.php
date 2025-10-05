<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'SIGN_IN_PAGE_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'SIGN_IN_PAGE_TEXT') ?></p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'sign-in-form']); ?>

            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                <?= Yii::t('app', 'SIGN_IN_PAGE_RESET_TEXT') . ' ' .
                Html::a(Yii::t('app', 'SIGN_IN_PAGE_RESET_LINK'), ['password-reset-request']) ?>.
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'BUTTON_SIGN_IN'),
                    ['class' => 'btn btn-primary', 'name' => 'sign-in-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>