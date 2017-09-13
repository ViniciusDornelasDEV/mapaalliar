<?php

//DEFINE CONSTANTS 
defined('SITE_URL')
    || define('SITE_URL', 'http://octopusti.com.br/');

return array(
    'db'   => array(
        'dsn' => 'mysql:dbname=bd_mapa_cdb;host=localhost',
        'username'      => 'root',
        'password'      => ''
    ),
	/*'db'   => array(
        'dsn' => 'mysql:dbname=bd_9boxrh;host=bd_9boxrh.mysql.dbaas.com.br',
        'username'      => 'bd_9boxrh',
        'password'      => 'sqlyt4da51241'
    ),*/
);