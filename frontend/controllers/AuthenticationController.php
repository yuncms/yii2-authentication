<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authentication\frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yuncms\authentication\models\Authentication;

/**
 * Class AuthenticationController
 * @package yuncms\user\controllers
 */
class AuthenticationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 4,
                'maxLength' => 5,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'captcha'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 认证首页
     * @return string
     */
    public function actionIndex()
    {
        /** @var Authentication|null $model */
        if (($model = Authentication::findOne(['user_id' => Yii::$app->user->id])) == null) {
            return $this->redirect(['create']);
        }
        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * 提交实名认证
     * @return string|Response
     */
    public function actionCreate()
    {
        /** @var Authentication $model */
        if (($model = Authentication::findOne(['user_id' => Yii::$app->user->id])) == null) {
            return $this->redirect(['index']);
        }
        $model->scenario = 'create';
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->id_file = UploadedFile::getInstance($model, 'id_file');
            $model->id_file1 = UploadedFile::getInstance($model, 'id_file1');
            $model->id_file2 = UploadedFile::getInstance($model, 'id_file2');
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 更新实名认证
     * @return string|Response
     */
    public function actionUpdate()
    {
        /** @var Authentication $model */
        if (($model = Authentication::findOne(['user_id' => Yii::$app->user->id])) == null) {
            return $this->redirect(['index']);
        }
        $model->scenario = 'update';
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->id_file = UploadedFile::getInstance($model, 'id_file');
            $model->id_file1 = UploadedFile::getInstance($model, 'id_file1');
            $model->id_file2 = UploadedFile::getInstance($model, 'id_file2');
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
