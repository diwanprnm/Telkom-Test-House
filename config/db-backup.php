<?php


return [

	'path' => storage_path() . '/tmp/',

	'mysql' => [
		'dump_command_path' => '/usr/bin/',
		'restore_command_path' => '/usr/bin/',
	],

	's3' => [
		'path' => ''
	],

    'compress' => false,
];

