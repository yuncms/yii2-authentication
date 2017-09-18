<?php

namespace yuncms\authentication\migrations;

use yii\db\Migration;

/**
 * Class M170916081737Add_defailt_settings
 */
class M170916081737Add_defailt_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->batchInsert('{{%settings}}', ['type', 'section', 'key', 'value', 'active', 'created', 'modified'], [
            ['boolean', 'authentication', 'enableMachineReview', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],

            ['string', 'authentication', 'idCardUrl', '@web/uploads/id_card', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['string', 'authentication', 'idCardPath', '@root/uploads/id_card', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{%settings}}', ['section' => 'authentication']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170916081737Add_defailt_settings cannot be reverted.\n";

        return false;
    }
    */
}
