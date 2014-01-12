<?php

use Illuminate\Database\Migrations\Migration;

class Installation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            $sql = "

            CREATE TABLE IF NOT EXISTS `users` (
            `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_fbUUID` varchar(255) NOT NULL,
            `user_emails` varchar(255) NOT NULL,
            `user_sms` varchar(255) NOT NULL,
            `user_time_at_work` varchar(255) NOT NULL,
            `user_journey_duration` int(10) unsigned NOT NULL DEFAULT '3600',
            `user_line_one` varchar(255) NOT NULL,
            `user_line_two` varchar(255) NOT NULL,
            `user_last_emailed` datetime NOT NULL,
            `user_last_sms` datetime NOT NULL,
            PRIMARY KEY (`user_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

";

			$messagesTable = "
			CREATE TABLE IF NOT EXISTS `messages` (
				`message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`ct_momsgid` nvarchar(32),
				`ct_from` varchar(50),
				`ct_to` varchar(50),
				`ct_timestamp` varchar(50),
				`ct_text`varchar(255),
				PRIMARY KEY (`message_id`)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;";
            DB::statement($sql, $messagesTable);

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
                $sql = "DROP TABLE users;";
                DB::statement($sql);
	}

}