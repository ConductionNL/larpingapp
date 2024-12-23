<?php

return [
	'resources' => [
		'characters' => ['url' => 'api/characters'],
		'abilities' => ['url' => 'api/abilities'],
		'effects' => ['url' => 'api/effects'],
		'events' => ['url' => 'api/events'],
		'items' => ['url' => 'api/items'],
		'players' => ['url' => 'api/players'],
		'skills' => ['url' => 'api/skills'],
		'templates' => ['url' => 'api/templates'], 
		'conditions' => ['url' => 'api/conditions'], 
	],
	'routes' => [
		// Page routes
		['name' => 'dashboard#page', 'url' => '/', 'verb' => 'GET'],
		['name' => 'characters#downloadPdf', 'url' => '/characters/{id}/download', 'verb' => 'GET'],
		['name' => 'settings#index', 'url' => '/settings', 'verb' => 'GET'],
		['name' => 'settings#create', 'url' => '/settings', 'verb' => 'POST'],
	],
];
