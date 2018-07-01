<?php

use yii\db\Migration;

/**
 * Class m180701_112737_create_vacation
 */
class m180701_112737_create_vacation extends Migration
{
    public function up()
    {
        $this->createTable('{{%vacation}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'employee_id' => $this->integer()->notNull(),
            'date_from' => $this->date()->notNull(),
            'date_to' => $this->date()->notNull(),
        ]);

        $this->createIndex('idx-vacation-order_id', '{{%vacation}}', 'order_id');
        $this->createIndex('idx-vacation-employee_id', '{{%vacation}}', 'employee_id');

        $this->addForeignKey('fk-vacation-order_id', '{{%vacation}}', 'order_id', '{{%order}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-vacation-employee_id', '{{%vacation}}', 'employee_id', '{{%employee}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%vacation}}');
    }
}
