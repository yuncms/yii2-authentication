<?php

use yii\helpers\Html;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
use xutl\inspinia\ActiveForm;
use yuncms\user\backend\models\Settings;

/* @var yii\web\View $this  */
/* @var yuncms\authentication\models\Settings $model  */

$this->title = Yii::t('authentication', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('authentication', 'Manage Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 authentication-update">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget([
                        'items' => [
                            [
                                'label' => Yii::t('authentication', 'Manage Authentication'),
                                'url' => ['index'],
                            ],
                            [
                                'label' => Yii::t('authentication', 'Settings'),
                                'url' => ['settings'],
                            ],
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal'
            ]); ?>

            <?= $form->field($model, 'enableMachineReview')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'ociAppCode') ?>
            <?= $form->field($model, 'idCardUrl') ?>
            <?= $form->field($model, 'idCardPath') ?>

            <?= Html::submitButton(Yii::t('authentication', 'Settings'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>