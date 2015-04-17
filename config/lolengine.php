<?php

return [
	/*
	|--------
	| API Key
	|--------------------------------------------
	| Get one at https://developer.riotgames.com/
	|--------------------------------------------
	*/
	'apiKey' => env('LE_API_KEY', ''),

	/*
	|-------------------------------------
	| Delay in seconds before next request
	|-------------------------------------------------------------------------------------------------------------------
	| Engine will wait for @minDelayBeforeRequest seconds until next request to API. Increase this parameter to decrease
	| server's load (not necessary)
	|-------------------------------------------------------------------------------------------------------------------
	*/
	'minDelayBeforeRequest' => 0,
];
