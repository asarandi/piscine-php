<?php
include_once 'minishop.php';
function import_csv_into_sql() {
    if (!$link = db_connect()){
        die("failed to connect to sql database\n");
    }

    /*
     * expecting to have file "csv_data/products.csv" that will be inserted into SQL TABLE products
     * expecting to have file "csv_data/users.csv" that will be inserted into SQL TABLE users
     * csv file format:
     *      --first line should match the column names in sql database
     */

    $tables = ["basket_items", "baskets", "categories", "category_items", "products", "users"];

    foreach ($tables as $table) {
        $file = 'csv_data/' . $table . '.csv';

        if (!$arrays = csv_to_array($file)) {
            die("csv_to_array() failed\n");
        }
        foreach ($arrays as $array) {
            $keys = array_keys($array);

            foreach ($keys as &$key) {
                $key = mysqli_real_escape_string($link, $key);
            }

            $values = array_values($array);
            foreach ($values as &$value) {
                $value = mysqli_real_escape_string($link, $value);
            }

            $query = sprintf(
                'INSERT INTO %s (%s) VALUES ("%s")',
                $table,
                implode(',', $keys),
                implode('","', $values)
            );
            echo "$query\n";
            if (!mysqli_query($link, $query)) {
                die("mysqli insert query failed");
            }
        }
    }
    mysqli_close($link);

    echo "data imported successfully\n";
}


$host = $GLOBALS['SQL_HOST'];
$user = $GLOBALS['SQL_USER'];
$password = $GLOBALS['SQL_PASSWORD'];
$database = $GLOBALS['SQL_DATABASE'];
$port = $GLOBALS['SQL_PORT'];
if (!($link = mysqli_connect($host, $user, $password, NULL, $port))) {
    die("failed to connect to mysql server\n");
}
if (!mysqli_query($link, "CREATE DATABASE IF NOT EXISTS $database;")) {
    mysqli_close($link);
    die("create database query failed\n");
}

if (!mysqli_select_db($link, $database)) {
    mysqli_close($link);
    die("select db function failed\n");
}

$queries = [
    'CREATE TABLE IF NOT EXISTS users          (id INTEGER PRIMARY KEY AUTO_INCREMENT, login TEXT, passwd TEXT, admin INTEGER,                      active INTEGER);',
    'CREATE TABLE IF NOT EXISTS products       (id INTEGER PRIMARY KEY AUTO_INCREMENT, title TEXT, description TEXT, photo TEXT, price INTEGER,     active INTEGER);',
    'CREATE TABLE IF NOT EXISTS categories     (id INTEGER PRIMARY KEY AUTO_INCREMENT, title TEXT, description TEXT, photo TEXT,                    active INTEGER);',
    'CREATE TABLE IF NOT EXISTS category_items (id INTEGER PRIMARY KEY AUTO_INCREMENT, category_id INTEGER, product_id INTEGER,                     active INTEGER);',
    'CREATE TABLE IF NOT EXISTS baskets        (id INTEGER PRIMARY KEY AUTO_INCREMENT, user_id INTEGER, time_created INTEGER,                       active INTEGER);',
    'CREATE TABLE IF NOT EXISTS basket_items   (id INTEGER PRIMARY KEY AUTO_INCREMENT, basket_id INTEGER, product_id INTEGER, product_qty INTEGER,  active INTEGER);',
];

foreach ($queries as $query) {
    if (($res = mysqli_query($link, $query)) === FALSE) {
        mysqli_close($link);
        die("create table query failed\n");
    }
}
echo "tables created successfully\n";

mysqli_close($link);
import_csv_into_sql();
?>
