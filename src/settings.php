<?php
return [
	'settings' => [
			'displayErrorDetails' => true, // set to false in production
            'addContentLengthHeader' => false, // Allow the web server to send the content-length header
            
            // medoo database settings
            'db'    =>  [
                'database_type' => getenv('DB_TYPE'),
                'database_name' => getenv('DB_NAME'),
                'server' => getenv('DB_HOST'),
                'username' => getenv('DB_USER'),
                'password' => getenv('DB_PASS'),
                'charset' => getenv('DB_CHARSET'),
                'port' => getenv('DB_PORT'),
                'prefix' => 't_',
                'option' => [
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]
            ],
    ],
];
