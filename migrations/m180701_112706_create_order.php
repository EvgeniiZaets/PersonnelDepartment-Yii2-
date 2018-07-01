<?php

use yii\db\Migration;

/**
 * Class m180701_112706_create_order
 */
class m180701_112706_create_order extends Migration
{
    public function up()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%order}}');
    }
}
