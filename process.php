<?php
error_reporting(8191);
require 'config.php';
require 'Lib/Alepay.php';
require 'Lib/ConnectDB/Database.php';


// Create database name
$actionCreateDB = @$_REQUEST['action'];
$alepay = new Alepay($config);
if ($actionCreateDB === 'createDatabase') {

    // create instance Database()
    $createDB = new Database();
    $resultCreateDB = $createDB->createDatabaseName($_POST['dbName']);
    $checkExitsTableName = $createDB->checkExistsTable(DB_TABLENAME);
    if ($checkExitsTableName === false){
        switch (DB_DBMS) {
            case 'postgres':
                $createDB->createTablesPostgres($_POST['tbName']);
                break;
            default:
                $createDB->createTablesMySQL($_POST['tbName']);
                break;
        }
    }
    header('location: '. '/alepay-sendcardlinkrequest' );
//    $alepay->return_json('OK', 'Tạo Database Thành Công');
}


$data = [];
$action = @$_REQUEST['action'];
parse_str(file_get_contents('php://input'), $params);
$arrName = explode(' ', $params['buyerName']);
$data['id'] = trim($params['buyerEmail']) . '-' . time();
$data['firstName'] = $arrName[0];
for ($i = 1; $i < count($arrName); $i++) {
    $data['lastName'] .= ' ' . $arrName[$i];
}
$data['lastName'] = trim($data['lastName']);
$data['street'] = trim($params['street']);
$data['city'] = trim($params['buyerCity']);
$data['state'] = trim($params['state']);
$data['postalCode'] = trim($params['postalCode']);
$data['country'] = trim($params['buyerCountry']);
$data['email'] = trim($params['buyerEmail']);
$data['phoneNumber'] = trim($params['phoneNumber']);
$data['callback'] = URL_CALLBACK;
foreach ($data as $k => $v) {
    if (empty($v)) {
        $alepay->return_json("NOK", "Bắt buộc phải nhập/chọn tham số [ " . $k . " ]");
        die();
    }
}
switch ($action) {
    case 'sendCardLinkRequest':
        $result = $alepay->sendCardLinkRequest($data);
        break;
    default:
        $result = $alepay->sendCardLinkRequest($data);
}
if (isset($result) && !empty($result->url)) {
    // insert $data for database
    $db = new Database();
    try {
        $convertedData = $db->convertKeysArrayToLower($data);
        $res = $db->insert(DB_TABLENAME, $convertedData);
        if ($res === true) {
            $alepay->return_json('OK', 'Thành công', $result->url);
        }
    } catch (PDOException $e) {
        $e->getMessage();
    }

} else {
    $alepay->return_json($result->errorCode, $result->errorDescription);
}