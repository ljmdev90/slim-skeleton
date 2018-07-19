<?php
return [
	'settings' => [
			'displayErrorDetails' => true, // set to false in production
            'addContentLengthHeader' => false, // Allow the web server to send the content-length header
            
            // medoo database settings
            'db'    =>  [
                'database_type' => 'mysql',
                'database_name' => 'test',
                'server' => '127.0.0.1',
                'username' => 'root',
                'password' => '123456',
                'charset' => 'utf8',
                'port' => 3306,
                'prefix' => 't_',
                'option' => [
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]
            ],
    ],
];
