<?php

namespace yuncms\authentication\migrations;

use yii\db\Migration;

/**
 * Class M170827090430Create_authentication_table
 */
class M170827090430Create_authentication_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%authentications}}', [
            'user_id' => $this->integer()->unsigned()->notNull(),
            'real_name' => $this->string(),
            'id_type' => $this->string(10)->notNull(),
            'id_card' => $this->string()->notNull(),
            'passport_cover' => $this->string(),
            'passport_person_page' => $this->string(),
            'passport_self_holding' => $this->string(),
            'status' => $this->smallInteger(1)->unsigned()->defaultValue(0),
            'failed_reason' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%authentications}}', '{{%authentications}}', 'user_id');
        $this->addForeignKey('{{%authentications_ibfk_1}}', '{{%authentications}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%authentications}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170827090430Create_authentication_table cannot be reverted.\n";

        return false;
    }
    */
}
