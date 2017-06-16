<?php
// ThanhNA (0968381829) - nguyenthanh.rise.88@gmail.com
//define('DB_DBMS', 'postgres');
//define('DB_HOST', 'localhost');
//define('DB_PORT', 5432);
//define('DB_USER', 'postgres');
//define('DB_PASS', '8888');
//define('DB_DBNAME', 'tododb');
//define('DB_TABLENAME', 'testusers123');
define('DB_DBMS', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', 80);
define('DB_USER', 'root');
define('DB_PASS', '8888');
define('DB_DBNAME', 'databasetest');
define('DB_TABLENAME', 'abcxyz');

//Thông tin cấu hình
define('URL_DEMO', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/alepay-sendcardlinkrequest/');
define('URL_CALLBACK', URL_DEMO . '/result.php'); // URL đón nhận kết quả
define('URL_TOKEN', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/alepay-tokenization/');
//Alepay cung cấp
$config = array(
    "apiKey" => "0COVspcyOZRNrsMsbHTdt8zesP9m0y", //Là key dùng để xác định tài khoản nào đang được sử dụng.
    "encryptKey" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCIh+tv4h3y4piNwwX2WaDa7lo0uL7bo7vzp6xxNFc92HIOAo6WPZ8fT+EXURJzORhbUDhedp8B9wDsjgJDs9yrwoOYNsr+c3x8kH4re+AcBx/30RUwWve8h/VenXORxVUHEkhC61Onv2Y9a2WbzdT9pAp8c/WACDPkaEhiLWCbbwIDAQAB", //Là key dùng để mã hóa dữ liệu truyền tới Alepay.
    "checksumKey" => "hjuEmsbcohOwgJLCmJlf7N2pPFU1Le", //Là key dùng để tạo checksum data.
    "callbackUrl" => URL_CALLBACK
);