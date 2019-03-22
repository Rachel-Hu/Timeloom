<?php 

$db['db_host'] = 'localhost';
$db['db_user'] = 'timeloom';
$db['db_password'] = '1qazxsw2';
$db['db_name'] = 'tasks';

foreach($db as $key => $value){
    define(strtoupper($key), $value);
}

$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(!$connect){
    echo "Database connection error: please check the settings";
}

?>