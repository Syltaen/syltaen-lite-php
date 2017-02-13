<?php

use Medoo\Medoo;

$db = new Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => '####',
	'server' => 'localhost',
	'username' => 'root',
	'password' => 'root',
	'charset' => 'utf8',

	// [optional]
	'port' => 3306,

	// [optional] Table prefix
	'prefix' => 'wsk_',

	// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	// 'option' => [
	// 	PDO::ATTR_CASE => PDO::CASE_NATURAL
	// ]

	// [optional] Medoo will execute those commands after connected to the database for initialization
	// 'command' => [
	// 	'SET SQL_MODE=ANSI_QUOTES'
	// ]
]);
