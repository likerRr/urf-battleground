<?php

return [
	'apiKey' => env('RIOT_API_KEY', ''),
	'limits' => [
		// requests, seconds
		[20, 10],
		[10, 10],
		[10, 1],
		[9, 10],
		[8, 11],
		[500, 600]
	],
];
