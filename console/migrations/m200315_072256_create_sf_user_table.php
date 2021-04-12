<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sf_user}}`.
 */
class m200315_072256_create_sf_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sf_user}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sf_user}}');
    }
}
