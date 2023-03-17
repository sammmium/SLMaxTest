<?php

return [

	'/' => [
		'controller' => 'DashboardController',
		'action' => 'index',
		'view' => 'dashboard/index.twig',
		'selected_menu_item' => 'dashboard'
	],

	'/person/generate' => [
		'controller' => 'DashboardController',
		'action' => 'person_generate',
		'view' => '',
		'selected_menu_item' => 'dashboard'
	],

	'/people/delete-selected' => [
		'controller' => 'DashboardController',
		'action' => 'people_selected_delete',
		'view' => '',
		'selected_menu_item' => 'dashboard'
	],

	'/person/delete/{id}' => [
		'controller' => 'DashboardController',
		'action' => 'person_delete',
		'view' => '',
		'selected_menu_item' => 'dashboard'
	],

];
