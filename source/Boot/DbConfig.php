<?php
/**
 * DATABASE
 */

if (strpos($_SERVER['HTTP_HOST'] ?? "", "ocalhost")) {
    /* LOCALHOST */
    define("CONF_DB_APP_HOST", "localhost");
    define("CONF_DB_APP_USER", "root");
    define("CONF_DB_APP_PASS", "");
    define("CONF_DB_APP_NAME", "db_protepop");
} else {
//    define('CONF_DB_APP_HOST', 'localhost');
//    define('CONF_DB_APP_USER', 'automaca_khangoo');
//    define('CONF_DB_APP_PASS', '!u1CKV?%BXn=');
//    define('CONF_DB_APP_NAME', 'automaca_db_khangoo');

    define('CONF_DB_APP_HOST', 'localhost');
    define('CONF_DB_APP_USER', '');
    define('CONF_DB_APP_PASS', '');
    define('CONF_DB_APP_NAME', '');
}


