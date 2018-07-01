<?php

use yii\db\Migration;

/**
 * Class m180701_112724_create_recruit
 */
class m180701_112724_create_recruit extends Migration
{
    public function up()
    {
        $this->createTable('{{%recruit}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'employee_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
        ]);

        $this->createIndex('idx-recruit-order_id', '{{%recruit}}', 'order_id');
        $this->createIndex('idx-recruit-employee_id', '{{%recruit}}', 'employee_id');

        $this->addForeignKey('fk-recruit-order_id', '{{%recruit}}', 'order_id', '{{%order}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-recruit-employee_id', '{{%recruit}}', 'employee_id', '{{%employee}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%recruit}}');
    }
}
