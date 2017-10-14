<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authentication\frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class AuthenticationForm
 * @package yuncms\authentication
 */
class Authentication extends \yuncms\authentication\models\Authentication
{
    /**
     * @var \yii\web\UploadedFile 身份证上传字段
     */
    public $id_file;

    /**
     * @var \yii\web\UploadedFile 身份证上传字段
     */
    public $id_file1;

    /**
     * @var \yii\web\UploadedFile 身份证上传字段
     */
    public $id_file2;

    /**
     * @var string 验证码
     */
    public $verifyCode;

    /**
     * @var bool 是否同意注册协议
     */
    public $registrationPolicy;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'create' => ['real_name', 'id_type', 'id_card', 'id_file', 'id_file1', 'id_file2'],
            'update' => ['real_name', 'id_type', 'id_card', 'id_file', 'id_file1', 'id_file2'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'idCardString' => [
                ['id_card'],
                'string',
                'when' => function ($model) {//中国大陆18位身份证号码
                    return $model->id_type == static::TYPE_ID;
                },
                'whenClient' => "function (attribute, value) {return jQuery(\"#authentication-id_type\").val() == '" . Authentication::TYPE_ID . "';}",
                'length' => 18,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            'idFile' => [
                ['id_file', 'id_file1', 'id_file2'],
                'file',
                'extensions' => 'gif,jpg,jpeg,png',
                'maxSize' => 1024 * 1024 * 2,
                'tooBig' => Yii::t('authentication', 'File has to be smaller than 2MB'),
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],

            // verifyCode needs to be entered correctly
            'verifyCode' => ['verifyCode', 'captcha', 'captchaAction' => '/authentication/authentication/captcha'],

            'registrationPolicyRequired' => ['registrationPolicy', 'required', 'skipOnEmpty' => false, 'requiredValue' => true,
                'message' => Yii::t('authentication', '{attribute} must be selected.')
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels, [
            'id_file' => Yii::t('authentication', 'Passport cover'),
            'id_file1' => Yii::t('authentication', 'Passport person page'),
            'id_file2' => Yii::t('authentication', 'Passport self holding'),
            'registrationPolicy' => Yii::t('authentication', 'Agree and accept Service Agreement and Privacy Policy'),
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $idCardPath = $this->getIdCardPath($this->user_id);
            if ($this->id_file && $this->id_file->saveAs($idCardPath . '_passport_cover_image.jpg')) {
                $this->passport_cover = $this->getIdCardUrl($this->user_id) . '_passport_cover_image.jpg';
            }
            if ($this->id_file1 && $this->id_file1->saveAs($idCardPath . '_passport_person_page_image.jpg')) {
                $this->passport_person_page = $this->getIdCardUrl($this->user_id) . '_passport_person_page_image.jpg';
            }
            if ($this->id_file2 && $this->id_file2->saveAs($idCardPath . '_passport_self_holding_image.jpg')) {
                $this->passport_self_holding = $this->getIdCardUrl($this->user_id) . '_passport_self_holding_image.jpg';
            }
            if (!$insert && $this->scenario == 'update') {
                $this->status = self::STATUS_PENDING;
            }
            return true;
        } else {
            return false;
        }
    }
}