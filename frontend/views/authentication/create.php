<?php
use xutl\bootstrap\filestyle\FilestyleAsset;

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
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>