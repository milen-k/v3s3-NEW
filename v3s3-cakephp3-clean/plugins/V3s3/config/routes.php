<?php
use Cake\Routing\Router;

Router::plugin(
	'V3s3',
	function ($routes) {
		$routes->connect(
			'/**',
			[
				'controller' => 'V3s3',
				'action' => 'put',
				'_method' => 'PUT',
			]
		);
		$routes->connect(
			'/**',
			[
				'controller' => 'V3s3',
				'action' => 'get',
				'_method' => 'GET',
			]
		);
		$routes->connect(
			'/**',
			[
				'controller' => 'V3s3',
				'action' => 'delete',
				'_method' => 'DELETE',
			]
		);
		$routes->connect(
			'/**',
			[
				'controller' => 'V3s3',
				'action' => 'post',
				'_method' => 'POST',
			]
		);
	}
);