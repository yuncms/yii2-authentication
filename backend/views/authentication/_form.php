<?php
use yii\helpers\Html;
use yuncms\authentication\models\Authentication;
use xutl\inspinia\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\authentication\models\Authentication $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>


<?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'id_type')->dropDownList([
    Authentication::TYPE_ID => Yii::t('authentication', 'ID Card'),
    Authentication::TYPE_PASSPORT => Yii::t('authentication', 'Passport ID'),
    Authentication::TYPE_ARMYID => Yii::t('authentication', 'Army ID'),
    Authentication::TYPE_TAIWANID => Yii::t('authentication', 'Taiwan ID'),
    Authentication::TYPE_HKMCID => Yii::t('authentication', 'HKMC ID'),
]); ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'id_card')->textInput(['maxlength' => true]) ?>

<div class="hr-line-dashed"></div>

<?= $form->field($model, 'status')->inline(true)->radioList([
    0 => Yii::t('authentication', 'Pending review'),
    1 => Yii::t('authentication', 'Refuse'),
    2 => Yii::t('authentication', 'Passed'),
]) ?>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_cover">
    <label class="control-label col-sm-3"><?= Yii::t('authentication', 'Passport cover') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_cover); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_person_page">
    <label class="control-label col-sm-3"><?= Yii::t('authentication', 'Passport person page') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_person_page); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_self_holding">
    <label class="control-label col-sm-3"><?= Yii::t('authentication', 'Passport self holding') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_self_holding); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'failed_reason')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('authentication', 'Create') : Yii::t('authentication', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    </div>
</div>


<?php ActiveForm::end(); ?>

