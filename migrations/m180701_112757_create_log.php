<?php

use yii\db\Migration;

/**
 * Class m180701_112757_create_log
 */
class m180701_112757_create_log extends Migration
{
    public function up()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'message' => $this->text(),
        ]);

        $this->createIndex('idx-log-user_id', '{{%log}}', 'user_id');
    }

    public function down()
    {
        $this->dropTable('{{%log}}');
    }
}
