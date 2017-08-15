<?php

use yii\db\Migration;

class m170810_062441_jql_table extends Migration
{
    public function safeUp()
    {

            $sql = <<<'EOD'
CREATE TABLE `jql` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `value` TEXT NOT NULL,
  `created_at` BIGINT NOT NULL,
  `updated_at` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `jql_user_id_idx` (`user_id` ASC),
  CONSTRAINT `jql_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);

EOD;
            Yii::$app->db->createCommand($sql)->execute();
        
    }

    public function safeDown()
    {
        echo "m170810_062441_jql_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170810_062441_jql_table cannot be reverted.\n";

        return false;
    }
    */
}
