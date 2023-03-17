<?php

return [
	'app_name' => 'Тестовое задание',
	'owner' => 'Евгений Самойлов',
	'developer' => 'sammmium.dev@gmail.com',

	'sections' => [
		['alias' => 'dashboard', 'name' => 'Главная', 'path' => '/', 'enabled' => true],
//		['alias' => 'schedules', 'name' => 'Расписание', 'path' => '/schedules', 'enabled' => true],
//		['alias' => 'contacts', 'name' => 'Контакты', 'path' => '/contacts', 'enabled' => true],
//		['alias' => 'reports', 'name' => 'Отчеты', 'path' => '/reports', 'enabled' => true]
	],

	'db' => [
		'name' => 'slmaxtest',
		'host' => 'localhost',
		'user' => 'root',
		'password' => 'phpdeveloper'
	]
];
