<?php
return array (
  'api_url' => ENV('NT_API_URL'),
  'api_key' => ENV('NT_API_KEY'),
  'external_keys' => [
  	'cdn' => [
  		'url' => ENV('CDN_API_URL'),
  		'key' => ENV('CDN_API_KEY')
  	]
  ]
);