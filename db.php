<?php

namespace db;

use Dotenv;

// SETUP .ENV READ
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

// DEFINE ENV VARS
// $FILERUN_URL = getenv('FILERUN_URL');
// $FILERUN_USER_NAMES = getenv('FILERUN_USER_NAMES');
// $FILERUN_USER_PASSWORDS = getenv('FILERUN_USER_PASSWORDS');

// $FILERUN_DB_HOST = getenv('FILERUN_DB_HOST');
// $FILERUN_DB_NAME = getenv('FILERUN_DB_NAME');
// $FILERUN_DB_USER = getenv('FILERUN_DB_USER');
// $FILERUN_DB_PASSWORD = getenv('FILERUN_DB_PASSWORD');

// $FILERUN_CLIENT_ID = getenv('FILERUN_CLIENT_ID');
// $FILERUN_CLIENT_SECRET = getenv('FILERUN_CLIENT_SECRET');

// CREATE CONNECTION
// echo "<hr>" . $FILERUN_DB_HOST . "  " . $FILERUN_DB_USER . "<br>";
// $connection = mysqli_connect($FILERUN_DB_HOST, $FILERUN_DB_USER, $FILERUN_DB_PASSWORD, $FILERUN_DB_NAME);

class FILERUN_CONNECTOR
{
    static $FILERUN_URL;
    static $FILERUN_USER_NAMES;
    static $FILERUN_USER_PASSWORDS;
    static $FILERUN_DB_HOST;
    static $FILERUN_DB_NAME;
    static $FILERUN_DB_USER;
    static $FILERUN_DB_PASSWORD;
    static $FILERUN_CLIENT_ID;
    static $FILERUN_CLIENT_SECRET;
    static $MYSQLCONN;

    public function __construct()
    {
        // Explode .env vars with multiple comma-separated entries into arrays
        self::$FILERUN_USER_NAMES = explode(',', getenv('FILERUN_USER_NAMES'));
        self::$FILERUN_USER_PASSWORDS = explode(',', getenv('FILERUN_USER_PASSWORDS'));

        // Simple .env variables
        self::$FILERUN_URL = getenv('FILERUN_URL');
        self::$FILERUN_DB_HOST = getenv('FILERUN_DB_HOST');
        self::$FILERUN_DB_NAME = getenv('FILERUN_DB_NAME');
        self::$FILERUN_DB_USER = getenv('FILERUN_DB_USER');
        self::$FILERUN_DB_PASSWORD = getenv('FILERUN_DB_PASSWORD');
        self::$FILERUN_CLIENT_ID = getenv('FILERUN_CLIENT_ID');
        self::$FILERUN_CLIENT_SECRET = getenv('FILERUN_CLIENT_SECRET');
        self::$MYSQLCONN = mysqli_connect(
            self::$FILERUN_DB_HOST,
            self::$FILERUN_DB_USER,
            self::$FILERUN_DB_PASSWORD,
            self::$FILERUN_DB_NAME
        );
    }
}
