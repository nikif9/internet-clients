<?php
/**
 * Конфигурационный файл.
 *
 * @return array
 */
return [
    'db' => [
        'host'    =>  getenv('DB_HOST') ?: 'localhost',
        'dbname'  => getenv('DB_NAME') ?:'my_db',
        'user'    => getenv('DB_USER') ?:'root',
        'pass'    => getenv('DB_PASS') ?:'',
        'charset' => 'utf8'
    ]
];
