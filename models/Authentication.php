<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authentication\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yuncms\user\models\User;
use yuncms\authentication\AuthenticationTrait;

/**
 * This is the model class for table "authentications".
 *
 * @property integer $user_id 用户ID
 * @property string $real_name 真实姓名
 * @property string $id_card 证件号
 * @property string $id_type 证件类型
 * @property string $passport_cover
 * @property string $passport_person_page
 * @property string $passport_self_holding
 * @property int $status 审核状态
 * @property string $failed_reason 拒绝原因
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 *
 * @property User $user
 */
class Authentication extends ActiveRecord
{
    use AuthenticationTrait;

    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新
    const SCENARIO_VERIFY = 'verify';
    //证件类型
    const TYPE_ID = 'id';
    const TYPE_PASSPORT = 'passport';
    const TYPE_ARMYID = 'armyid';
    const TYPE_TAIWANID = 'taiwan';
    const TYPE_HKMCID = 'hkmcid';

    //认证状态
    const STATUS_PENDING = 0b0;
    const STATUS_REJECTED = 0b1;
    const STATUS_AUTHENTICATED = 0b10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%authentications}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior'
            ],

            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            self::SCENARIO_CREATE => ['real_name', 'id_type', 'id_card'],
            self::SCENARIO_UPDATE => ['real_name', 'id_type', 'id_card'],
            self::SCENARIO_VERIFY => ['real_name', 'id_card', 'status', 'failed_reason'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //realName rule
            'realNameRequired' => ['real_name', 'required'],
            'realNameTrim' => ['real_name',  'trim'],

            //idCard rule
            'idCardRequired' => ['id_card', 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            'idCardString' => [
                ['id_card'],
                'string',
                'when' => function ($model) {//中国大陆18位身份证号码
                    return $model->id_type == static::TYPE_ID;
                },
                'length' => 18,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            'idCardMatch' => [
                'id_card',
                'yuncms\system\validators\IdCardValidator',
                'when' => function ($model) {//中国大陆18位身份证号码校验
                    return $model->id_type == static::TYPE_ID;
                },
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            'idCardTrim' => ['id_card', 'trim'],

            //idType rule
            'idTypeRange' => [
                'id_type',
                'in',
                'range' => [
                    self::TYPE_ID, self::TYPE_PASSPORT, self::TYPE_ARMYID, self::TYPE_TAIWANID, self::TYPE_HKMCID
                ],
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],

            //status rule
            'statusDefault' => [
                'status',
                'default',
                'value' => self::STATUS_PENDING,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE, self::SCENARIO_VERIFY]
            ],
            'StatusRange' => [
                'status',
                'in',
                'range' => [self::STATUS_PENDING, self::STATUS_REJECTED, self::STATUS_AUTHENTICATED],
                'on' => [self::SCENARIO_VERIFY]
            ],

            //failed_reason rule
            'failedReasonTrim' => [
                'failed_reason',
                'filter',
                'filter' => 'trim',
                'on' => [self::SCENARIO_VERIFY]
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
            'passport_cover' => Yii::t('authentication', 'Passport cover'),
            'passport_person_page' => Yii::t('authentication', 'Passport person page'),
            'passport_self_holding' => Yii::t('authentication', 'Passport self holding'),
            'status' => Yii::t('authentication', 'Status'),
            'failed_reason' => Yii::t('authentication', 'Failed Reason'),
            'created_at' => Yii::t('authentication', 'Created At'),
            'updated_at' => Yii::t('authentication', 'Updated At'),
        ];
    }

    public function getType()
    {
        switch ($this->id_type) {
            case self::TYPE_ID:
                $genderName = Yii::t('authentication', 'ID Card');
                break;
            case self::TYPE_PASSPORT:
                $genderName = Yii::t('authentication', 'Passport ID');
                break;
            case self::TYPE_ARMYID:
                $genderName = Yii::t('authentication', 'Army ID');
                break;
            case self::TYPE_TAIWANID:
                $genderName = Yii::t('authentication', 'Taiwan ID');
                break;
            case self::TYPE_HKMCID:
                $genderName = Yii::t('authentication', 'HKMC ID');
                break;
            default:
                throw new \RuntimeException('Not set!');
        }
        return $genderName;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 是否实名认证
     * @param int $user_id
     * @return bool
     */
    public static function isAuthentication($user_id)
    {
        $user = static::findOne(['user_id' => $user_id]);
        return $user ? $user->status == static::STATUS_AUTHENTICATED : false;
    }

    /**
     * 删除前先删除附件
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $idCardPath = $this->getIdCardPath($this->user_id);
            if (file_exists($idCardPath . '_passport_cover_image.jpg')) {
                @unlink($idCardPath . '_passport_cover_image.jpg');
            }
            if (file_exists($idCardPath . '_passport_person_page_image.jpg')) {
                @unlink($idCardPath . '_passport_person_page_image.jpg');
            }
            if (file_exists($idCardPath . '_passport_self_holding_image.jpg')) {
                @unlink($idCardPath . '_passport_self_holding_image.jpg');
            }
            return true;
        } else {
            return false;
        }
    }
}