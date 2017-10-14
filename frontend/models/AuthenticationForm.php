<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authentication\frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yuncms\authentication\AuthenticationTrait;
use yuncms\authentication\models\Authentication;

/**
 * Class AuthenticationForm
 * @package yuncms\authentication
 */
class AuthenticationForm extends Authentication
{
    /**
     * @var int 证件类别
     */
    public $id_type;

    /**
     * @var string 真实姓名
     */
    public $real_name;

    /**
     * @var string 证件号
     */
    public $id_card;

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
        return [
            [['real_name', 'id_card', 'id_file', 'id_file1', 'id_file2', 'verifyCode'], 'required', 'on' => ['create', 'update']],
            [['real_name', 'id_card',], 'filter', 'filter' => 'trim'],

            [['id_card'], 'string', 'when' => function ($model) {//中国大陆18位身份证号码
                return $model->id_type == Authentication::TYPE_ID;
            }, 'whenClient' => "function (attribute, value) {return jQuery(\"#authentication-id_type\").val() == '" . Authentication::TYPE_ID . "';}",
                'length' => 18, 'on' => ['create', 'update']],

            ['id_card', 'yuncms\system\validators\IdCardValidator', 'when' => function ($model) {//中国大陆18位身份证号码校验
                return $model->id_type == Authentication::TYPE_ID;
            }, 'on' => ['create', 'update']],

            [['id_file'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('authentication', 'File has to be smaller than 2MB'), 'on' => ['create', 'update']],
            [['id_file1'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('authentication', 'File has to be smaller than 2MB'), 'on' => ['create', 'update']],
            [['id_file2'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('authentication', 'File has to be smaller than 2MB'), 'on' => ['create', 'update']],

            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'captchaAction' => '/authentication/authentication/captcha'],

            'registrationPolicyRequired' => ['registrationPolicy', 'required', 'skipOnEmpty' => false, 'requiredValue' => true,
                'message' => Yii::t('authentication', '{attribute} must be selected.')
            ],

            ['id_type', 'in', 'range' => [
                Authentication::TYPE_ID,
                Authentication::TYPE_PASSPORT,
                Authentication::TYPE_ARMYID,
                Authentication::TYPE_TAIWANID,
                Authentication::TYPE_HKMCID],
                'on' => ['create', 'update']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('authentication', 'User Id'),
            'real_name' => Yii::t('authentication', 'Full Name'),
            'id_type' => Yii::t('authentication', 'Id Type'),
            'type' => Yii::t('authentication', 'Id Type'),
            'id_card' => Yii::t('authentication', 'Id Card'),
            'id_file' => Yii::t('authentication', 'Passport cover'),
            'id_file1' => Yii::t('authentication', 'Passport person page'),
            'id_file2' => Yii::t('authentication', 'Passport self holding'),
            'passport_cover' => Yii::t('authentication', 'Passport cover'),
            'passport_person_page' => Yii::t('authentication', 'Passport person page'),
            'passport_self_holding' => Yii::t('authentication', 'Passport self holding'),
            'status' => Yii::t('authentication', 'Status'),
            'failed_reason' => Yii::t('authentication', 'Failed Reason'),
            'verifyCode' => Yii::t('authentication', 'Verify Code'),
            'idCardUrl' => Yii::t('authentication', 'Id Card Image'),
            'created_at' => Yii::t('authentication', 'Created At'),
            'updated_at' => Yii::t('authentication', 'Updated At'),
            'registrationPolicy' => Yii::t('authentication', 'Agree and accept Service Agreement and Privacy Policy'),
        ];
    }
}