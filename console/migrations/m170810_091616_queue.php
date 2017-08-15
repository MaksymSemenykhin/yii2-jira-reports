<?php

use yii\db\Migration;

class m170810_091616_queue extends Migration
{
    public function safeUp()
    {
        

            $sql = <<<'EOD'
CREATE TABLE `queue` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `type` ENUM('jql_aggregation') NOT NULL,
  `status` ENUM('new','in_progress','done') NOT NULL DEFAULT 'new',
  `progress` INT NOT NULL,
  `created_at` INT(11) NOT NULL,
  `updated_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`));
  
ALTER TABLE `queue` 
ADD COLUMN `data` TEXT NOT NULL AFTER `updated_at`;

ALTER TABLE `queue` 
CHANGE COLUMN `progress` `progress` INT(11) NOT NULL DEFAULT 0 ;

ALTER TABLE `queue` 
ADD COLUMN `dataFrom` INT NOT NULL AFTER `data`,
ADD COLUMN `dataTo` INT NOT NULL AFTER `dataFrom`;

ALTER TABLE `queue` 
ADD COLUMN `spend` INT NULL AFTER `dataTo`,
ADD COLUMN `estimation` INT NULL AFTER `spend`;

ALTER TABLE `queue` 
CHANGE COLUMN `data` `data` LONGTEXT NOT NULL ;

ALTER TABLE `queue` 
ADD COLUMN `issues` INT NULL AFTER `estimation`,
ADD COLUMN `worklogs` INT NULL AFTER `issues`;

ALTER TABLE `queue` 
CHANGE COLUMN `status` `status` ENUM('new','in_progress','done','error') NOT NULL DEFAULT 'new' ;


EOD;
            Yii::$app->db->createCommand($sql)->execute();
                

    }

    public function safeDown()
    {
        echo "m170810_091616_queue cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170810_091616_queue cannot be reverted.\n";

        return false;
    }
    */
}
