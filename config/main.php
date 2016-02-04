<?php
return [
	'uploadDir'		=> '/files/',
	'images'		=> [
		'json' 		=> '/images.json',	
		'delimeter'	=> '_',
		'full'		=> [
			'path' 		=> '/images/',
			'height'	=> 1200,
			'width'		=> 1900,
			'ext'		=> 'jpg'
		],
		'low'		=> [
			'path'		=> '/images/thumbs/',
			'height'	=> 300,
			'width'		=> 300,
			'ext'		=> 'png'
		]
	]	
];
