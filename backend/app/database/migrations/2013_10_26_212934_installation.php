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
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2013 at 02:18 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `disrupt`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
  `user_lat_sms` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

";

            DB::statement($sql);

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