<?php

use yii\db\Migration;

/**
 * Class m180701_101116_create_employee
 */
class m180701_101116_create_employee extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'email' => $this->string(),
            'status' => $this->smallInteger()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-employee-status', '{{%employee}}', 'status');
    }

    public function down()
    {
        $this->dropTable('{{%employee}}');
    }
}
