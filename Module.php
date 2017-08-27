<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\authentication;

use Yii;
use yii\helpers\FileHelper;

/**
 * Class Module
 * @package yuncms\authentication
 */
class Module extends \yii\base\Module
{
    public $idCardUrl = '@web/uploads/id_card';

    public $idCardPath = '@root/uploads/id_card';

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * 注册语言包
     * @return void
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['authentication*'])) {
            Yii::$app->i18n->translations['authentication*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }

    /**
     * 获取头像路径
     *
     * @param int $userId 用户ID
     * @return string
     */
    public function getHome($userId)
    {
        $id = sprintf("%09d", $userId);
        $dir1 = substr($id, 0, 3);
        $dir2 = substr($id, 3, 2);
        $dir3 = substr($id, 5, 2);
        return $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
    }

    /**
     * 获取身份证的存储路径
     * @param int $userId
     * @return string
     */
    public function getIdCardPath($userId)
    {
        $avatarPath = Yii::getAlias($this->idCardPath) . '/' . $this->getHome($userId);
        if (!is_dir($avatarPath)) {
            FileHelper::createDirectory($avatarPath);
        }
        return $avatarPath . substr($userId, -2);
    }

    /**
     * 获取身份证访问Url
     * @param int $userId 用户ID
     * @return string
     */
    public function getIdCardUrl($userId)
    {
        return Yii::getAlias($this->idCardUrl) . '/' . $this->getHome($userId) . substr($userId, -2);
    }
}
