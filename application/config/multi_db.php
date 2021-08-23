<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#TODO: Internal Database Configuration
$config['internal'] = [
  'hostname' => 'localhost',
  'username' => '',
  'password' => '',
  'database' => '',
  'dbdriver' => 'mysqli',
  'dbprefix' => '',
  'pconnect' => FALSE,
  'db_debug' => (ENVIRONMENT !== 'production'),
  'cache_on' => FALSE,
  'cachedir' => '',
  'char_set' => 'utf8mb4',
  'dbcollat' => 'utf8mb4_general_ci',
  'swap_pre' => '',
  'encrypt' => FALSE,
  'compress' => FALSE,
  'stricton' => FALSE,
  'failover' => array(),
  'save_queries' => TRUE
];
#TODO: Users Database Configuration
$config['users'] = [
  'hostname' => 'localhost',
  'username' => '',
  'password' => '',
  'database' => '',
  'dbdriver' => 'mysqli',
  'dbprefix' => '',
  'pconnect' => FALSE,
  'db_debug' => (ENVIRONMENT !== 'production'),
  'cache_on' => FALSE,
  'cachedir' => '',
  'char_set' => 'utf8mb4',
  'dbcollat' => 'utf8mb4_general_ci',
  'swap_pre' => '',
  'encrypt' => FALSE,
  'compress' => FALSE,
  'stricton' => FALSE,
  'failover' => array(),
  'save_queries' => TRUE
];
