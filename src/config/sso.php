<?php
return array (
  'authentication_url' => ENV('SSO_URL'),
  'id' => ENV('SSO_ID', 0),
  'token' => ENV('SSO_TOKEN'),

  'useFaker' => env('SSO_ENABLE_FAKER', false),

  'faker' => [
    'username' => 'faker',
    'store' => [
        'number' => '15212',
        'name' => 'Faker Enterprises LLC.',
    ],
    'first_name' => 'Faker',
    'last_name' => 'McFakerson',
    'entityKey' => 42,
    'email' => 'faker@fakerllc.com',
    'permisssions' => [
        'default::access_site'
    ]
  ]
);