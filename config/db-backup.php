<?php


return [

	'path' => storage_path() . '/app/public/backup-data/',

	'mysql' => [
		'dump_command_path' => 'C:\\xampp\\mysql\\bin\\',
		'restore_command_path' => 'C:\\xampp\\mysql\\bin\\',
	],

	's3' => [
		'path' => ''
	],

    'compress' => false,
];

