<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * work for nginx
 */
function multidb_connect($db)
{
    $config['hostname'] = $_ENV['DB_HOST'];
    $config['username'] = $_ENV['DB_USER'];
    $config['password'] = $_ENV['DB_PASS'];
    $config['port']     = $_ENV['DB_PORT'];
    $config['database'] = $db;
    $config['dbdriver'] = 'postgre';
    $config['dbprefix'] = '';
    $config['pconnect'] = FALSE;
    $config['db_debug'] = (ENVIRONMENT !== 'production');
    $config['cache_on'] = FALSE;
    $config['cachedir'] = '';
    $config['char_set'] = 'utf8';
    $config['dbcollat'] = 'utf8_general_ci';

    return $config;
}
