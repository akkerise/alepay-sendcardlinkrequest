<?php
/**
 * Created by PhpStorm.
 * User: akke
 * Date: 6/11/17
 * Time: 10:54 PM
 */
require 'config.php';
require 'Lib/Alepay.php';
require 'Lib/ConnectDB/Database.php';

$alepay = new Alepay($config);
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
    try{
        $convertedData = $db->convertKeysArrayToLower($data);
        $res = $db->insert('users', $convertedData);
        if ($res === true){
            $alepay->return_json('OK', 'Thành công', $result->url);
        }
    }catch (PDOException $e){
        $e->getMessage();
    }



} else {
    $alepay->return_json($result->errorCode, $result->errorDescription);
}