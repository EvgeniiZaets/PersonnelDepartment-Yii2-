<?php

use yii\db\Migration;

/**
 * Class m180701_112745_create_dismiss
 */
class m180701_112745_create_dismiss extends Migration
{
    public function up()
    {
        $this->createTable('{{%dismiss}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'employee_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'reason' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx-dismiss-order_id', '{{%dismiss}}', 'order_id');
        $this->createIndex('idx-dismiss-employee_id', '{{%dismiss}}', 'employee_id');

        $this->addForeignKey('fk-dismiss-order_id', '{{%dismiss}}', 'order_id', '{{%order}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-dismiss-employee_id', '{{%dismiss}}', 'employee_id', '{{%employee}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%dismiss}}');
    }
}
