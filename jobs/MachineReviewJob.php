<?php

namespace yuncms\authentication\jobs;

use yii\base\Object;
use yii\queue\RetryableJob;

/**
 * 机器审核任务类
 */
class MachineReviewJob extends Object implements RetryableJob
{
    /**
     * @var string 真是姓名
     */
    public $real_name;

    /**
     * @var string 身份证号码
     */
    public $id_card;

    /**
     * @var string 图片文件Base64
     */
    public $id_file;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        $input = [
            'image' => [
                'dataType' => '50',
                'dataValue' => 'base64',
            ],
            'configure' => [
                'dataType' => 50,
                'dataValue' => '{"side":"face"}',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}
