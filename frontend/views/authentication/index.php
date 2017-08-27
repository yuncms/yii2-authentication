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
 * @var yuncms\authentication\models\Authentication $model
 */

$this->title = Yii::t('authentication', 'Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <h2 class="h3 profile-title"><?= Yii::t('authentication', 'Authentication') ?></h2>
        <?php if ($model): ?>
            <?php if ($model->status == 0): ?>
                <div class="alert alert-info" role="alert">
                    <?= Yii::t('authentication', 'Your application is submitted successfully! We will be processed within three working days, the results will be processed by mail, station message to inform you, if in doubt please contact the official administrator.') ?>
                </div>
            <?php elseif ($model->status == 1): ?>
                <div class="alert alert-danger" role="alert">
                    <?= Yii::t('authentication', 'Sorry, after passing your review, the information you submitted has not been approved. Please check the information and submit it again.') ?>
                    <?php if ($model->failed_reason): ?>
                        <?= Yii::t('authentication', 'Failure reason:') ?><?= $model->failed_reason ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <?php if (!$model->isNewRecord): ?>
                    <div class="box box-solid">
                        <div class="box-body">
                            <dl class="dl-horizontal">
                                <dt><?= Yii::t('authentication', 'Full Name') ?></dt>
                                <dd><?= $model->real_name ?></dd>
                                <dt><?= Yii::t('authentication', 'Email') ?></dt>
                                <dd><?= Yii::$app->user->identity->email ?></dd>
                                <dt><?= Yii::t('authentication', 'Id Type') ?></dt>
                                <dd><?= $model->type ?></dd>
                                <dt><?= Yii::t('authentication', 'Id Card') ?></dt>
                                <dd><?= $model->id_card ?></dd>
                                <dt><?= Yii::t('authentication', 'Id Card Image') ?></dt>
                                <dd><img class="img-responsive"
                                         src="<?= $model->passport_cover ? base64_encode(file_get_contents($model->passport_cover)) : '' ?>"/>
                                </dd>
                                <dt><?= Yii::t('authentication', 'Id Card Image') ?></dt>
                                <dd><img class="img-responsive"
                                         src="<?= $model->passport_person_page ? base64_encode(file_get_contents($model->passport_person_page)) : '' ?>"/>
                                </dd>
                                <dt><?= Yii::t('authentication', 'Id Card Image') ?></dt>
                                <dd><img class="img-responsive"
                                         src="<?= $model->passport_self_holding ? base64_encode(file_get_contents($model->passport_self_holding)) : '' ?>"/>
                                </dd>
                                <dd><a href="<?= Url::to(['/authentication/authentication/update']) ?>"
                                       class="btn btn-warning">修改认证资料</a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                <?php else: ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
