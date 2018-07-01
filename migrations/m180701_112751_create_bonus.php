<?php

use yii\db\Migration;

/**
 * Class m180701_112751_create_bonus
 */
class m180701_112751_create_bonus extends Migration
{
    public function up()
    {
        $this->createTable('{{%bonus}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'employee_id' => $this->integer()->notNull(),
            'cost' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-bonus-order_id', '{{%bonus}}', 'order_id');
        $this->createIndex('idx-bonus-employee_id', '{{%bonus}}', 'employee_id');

        $this->addForeignKey('fk-bonus-order_id', '{{%bonus}}', 'order_id', '{{%order}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-bonus-employee_id', '{{%bonus}}', 'employee_id', '{{%employee}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%bonus}}');
    }
}
