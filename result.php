<?php
session_start();
require('Lib/Alepay.php');
require 'config.php';
require 'Lib/ConnectDB/Database.php';


$encryptKey = $config['encryptKey'];
if (isset($_REQUEST['data']) && isset($_REQUEST['checksum'])) {
    $utils = new AlepayUtils();
    $result = $utils->decryptCallbackData($_REQUEST['data'], $encryptKey);
    $obj_data = json_decode($result);
    try {
        $db = new Database();
        // update token by customerId after used sendCardLinkRequest
        $insertData['token'] = $obj_data->data->token;
        // insert string JSON vào field informations
        $insertData['informations'] = $result;
        $whereData['customerid'] = $obj_data->data->customerId;

        // update token cho khách hàng vào CSDL
        $res = $db->update(DB_TABLENAME, $insertData, $whereData);
        $_SESSION['customerid'] = $whereData;
    } catch (PDOException $e) {
        $e->getMessage();
        die();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
    <link rel="stylesheet" href="style/style.css">
    <title>Show Data</title>
    <style>
        #titleData {
            color: darkblue;
        }
    </style>
</head>
<body>
<div id="container">
    <div class="row">
        <div class="col s3"></div>
        <div class="col s6 center">
            <h3>Kết quả</h3>
            <ul class="collection col-md-8">

                <li class="collection-item">
                    <div>
                        <p>
                            <?php
                            if ($obj_data->errorCode === '000') {
                                // Xử lý khi giao dịch chờ xác nhận
                                echo "Liên Kết Thẻ Thành Công!<br>";
                            } else {
                                echo "Giao Dịch Thất Bại Mời Tra Trong Tài Liệu Để Biết Rõ Hơn ! Mã lỗi: " . $obj_data->errorCode;
                            }
                            ?>
                        </p>
                    </div>
                </li>
                <li class="collection-item">
                    <?php if (isset($obj_data->data)) {
                        foreach ($obj_data->data as $k => $v) { ?>
                            <?php if ($k === 'token') { ?>
                                <div>
                                    <h6 id="titleData"><?php echo $k . ' (Giá trị này nên lưu vào database)' ?></h6>
                                    <p><?php echo $v ?></p>
                                </div>
                                <?php continue; } ?>
                            <div>
                                <h6 id="titleData"><?php echo $k ?></h6>
                                <p><?php echo $v ?></p>
                            </div>
                        <?php }
                    } ?>
                </li>
                <li>
                    <?php if ($res === true) { ?>
                        <div>
                            <a href="<?php echo URL_DEMO . '/' . $whereData['customerid']  ?>">TRỞ LẠI TRANG LIÊN KẾT THẺ</a>
                        </div>
                    <?php } ?>
                </li>
                <li>
                    <?php if ($res === true) { ?>
                        <div>
                            <a href="<?php echo URL_TOKEN ?>">THANH TOÁN BẰNG TOKENIZATION</a>
                        </div>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
