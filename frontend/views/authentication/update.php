<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use xutl\bootstrap\filestyle\FilestyleAsset;
use yuncms\authentication\models\Authentication;

FilestyleAsset::register($this);
/*
 * @var yii\web\View $this
 * @var yuncms\user\models\Authentication $model
 */

$this->title = Yii::t('authentication', 'Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <h2 class="h3 profile-title"><?= Yii::t('authentication', 'Authentication') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                ]); ?>

                <?= $form->field($model, 'real_name') ?>
                <?= $form->field($model, 'id_type')->dropDownList([
                    Authentication::TYPE_ID => Yii::t('authentication', 'ID Card'),
                    Authentication::TYPE_PASSPORT => Yii::t('authentication', 'Passport ID'),
                    Authentication::TYPE_ARMYID => Yii::t('authentication', 'Army ID'),
                    Authentication::TYPE_TAIWANID => Yii::t('authentication', 'Taiwan ID'),
                    Authentication::TYPE_HKMCID => Yii::t('authentication', 'HKMC ID'),
                ]); ?>
                <?= $form->field($model, 'id_card') ?>
                <?= $form->field($model, 'id_file')->fileInput(['class' => 'filestyle', 'data' => [
                    'buttonText' => Yii::t('authentication', 'Choose file')
                ]]); ?>
                <?= $form->field($model, 'id_file1')->fileInput(['class' => 'filestyle', 'data' => [
                    'buttonText' => Yii::t('authentication', 'Choose file')
                ]]); ?>
                <?= $form->field($model, 'id_file2')->fileInput(['class' => 'filestyle', 'data' => [
                    'buttonText' => Yii::t('authentication', 'Choose file')
                ]]); ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'captchaAction' => '/authentication/authentication/captcha',

                ]); ?>

                <?= $form->field($model, 'registrationPolicy')->checkbox()->label(
                    Yii::t('authentication', 'Agree and accept {serviceAgreement} and {privacyPolicy}', [
                        'serviceAgreement' => Html::a(Yii::t('authentication', 'Service Agreement'), ['/legal/terms']),
                        'privacyPolicy' => Html::a(Yii::t('authentication', 'Privacy Policy'), ['/legal/privacy']),
                    ]), [
                        'encode' => false
                    ]
                ) ?>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?= Html::submitButton(Yii::t('authentication', 'Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
