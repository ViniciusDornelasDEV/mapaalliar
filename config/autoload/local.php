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
        'dsn' => 'mysql:dbname=mapa_alliar;host=mapa_alliar.mysql.dbaas.com.br',
        'username'      => 'mapa_alliar',
        'password'      => 'sqlyt4da51241'
    ),*/
    /*'db'   => array(
        'dsn' => 'mysql:dbname=timesistemamap;host=timesistemamap.mysql.dbaas.com.br',
        'username'      => 'timesistemamap',
        'password'      => 'sqlyt4da51241'
    ),*/
);