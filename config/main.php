<?php
return [
	'uploadDir'		=> '/files/',
	'images'		=> [
		'json' 		=> 'http://res.cloudinary.com/dexwggeql/raw/upload/v1480158255/images.json',	
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
