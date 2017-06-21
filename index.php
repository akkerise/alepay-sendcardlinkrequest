<?php
session_start();
require('config.php');
require('Lib/ConnectDB/Database.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Nhập Thông Tin</title>
    <style type="text/css">
        .require {
            color: red;
        }

        .form-group.col-sm-5 {
            margin: 5px;
        }
    </style>
</head>
<body>
<?php
require_once 'config.php';
?>
<div id="container" class="container">
    <div class="col-sm-5">
        <div class="col-sm-12">
            <h4><span class="require">Bước 1: </span>Tạo Database Để Lưu Dữ Liệu</h4>
            <form class="form" role="form" method="POST" id="formSubmitCreateDB"
                  action="<?= URL_DEMO ?>process.php?action=createDatabase">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="form-group col-sm-5">
                        <label class="control-label">Database Name : <span class="require">(*)</span></label>
                        <input type="text" placeholder="Database Name" class="form-control" name="dbName" id="dbName"
                               value="databasetest" required>
                    </div>
                </div>
                <div class="row"></div>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="form-group col-sm-5">
                        <label class="control-label">Table Name : <span class="require">(*)</span></label>
                        <input type="text" placeholder="Table Name" class="form-control" name="tbName" id="tbName"
                               value="abcxyz" required>
                    </div>
                </div>
                <div class="row"></div>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-5">
                        <div class="form-group col-sm-3">
                            <button id="submitDb" type="submit" class="btn btn-info btn-lg">
                                Tạo Database
                            </button>
                        </div>
                    </div>
                </div>

            </form>
            <br>
            <div class="row">

            </div>
            <div class="row">
                <p>
                    <span class="require">
                Lưu ý :
            </span> Sau khi khởi tạo thành công bạn hãy nhập lại thông tin tại file config.php
                </p>
                <ul>
                    <li>DB_DBMS (hệ quản trị dữ liệu bạn dùng ) : mysql</li>
                    <li>DB_HOST : localhost</li>
                    <li>DB_PORT : 80</li>
                    <li>DB_USER : root</li>
                    <li>DB_PASS (mặc định là rỗng) : ""</li>
                    <li>DB_DBNAME (tên database bạn vừa khởi tạo): databasetest</li>
                    <li>DB_TABLENAME (tên bảng table bạn vừa khởi tạo): abcxyz</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-sm-7">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h4><span class="require">Bước 2: </span>Tạo Liên Kết Thẻ Đến Alepay</h4>
            </div>
        </div>
        <form class="form-" role="form" method="POST" id="formSubmit"
              action="<?= URL_DEMO ?>process.php?action=sendCardLinkRequest">
            <div class="form-group col-sm-5">
                <label class="control-label">Email <span class="require">(*)</span></label>
                <input type="text" placeholder="Email" class="form-control" name="buyerEmail" id="buyerEmail"
                       value="thanhna@peacesoft.net" required>
            </div>
            <!-- Text input-->
            <div class="form-group col-sm-5">
                <label class="control-label">Họ Tên <span class="require">(*)</span></label>
                <input type="text" placeholder="Tên" class="form-control" name="buyerName" id="buyerName"
                       value="Nguyễn ABC" required>
            </div>
            <div class="form-group col-sm-5">
                <label class="control-label">Số Điện Thoại <span class="require">(*)</span></label>
                <input type="text" placeholder="Số Điện Thoại" class="form-control" name="phoneNumber" id="phoneNumber"
                       value="0988888888" required>
            </div>
            <div class="form-group col-sm-5">
                <label class="control-label">Quốc Gia <span class="require">(*)</span></label>
                <input type="text" placeholder="" class="form-control" name="buyerCountry" id="buyerCountry"
                       value="Việt Nam" required>
            </div>
            <div class="form-group col-sm-5">
                <label class="control-label">Vùng <span class="require">(*)</span></label>
                <input type="text" placeholder="" class="form-control" name="state" id="state"
                       value="Quận Hai Bà Trưng" required>
            </div>
            <div class="form-group col-sm-5">
                <label class="control-label" for="street">Đường <span class="require">(*)</span></label>
                <input type="text" placeholder="Đường" class="form-control" value="Hai Bà Trưng" name="street"
                       id="street" required>
            </div>
            <div class="form-group col-sm-5">
                <label class="control-label">Thành Phố <span class="require">(*)</span></label>
                <input type="text" placeholder="Thành Phố" class="form-control" name="buyerCity" id="buyerCity"
                       value="Hà Nội" required>
            </div>
            <div class="form-group col-sm-5">
                <label class="control-label">Mã Bưu Chính <span class="require">(*)</span></label>
                <input type="number" size="15" placeholder="Mã Bưu Chính" class="form-control" name="postalCode"
                       id="postalCode"
                       value="10000" required>
            </div>
            <div class="row"></div>
            <div class="col-sm-12" id="alert"></div>
            <div class="form-group col-sm-8">
                <p>&nbsp;</p>
                <div class="col-sm-5"></div>
                <button id="sendInstallment" type="button" class="btn btn-info btn-lg">
                    Liên kết thẻ Alepay
                </button>
            </div>
        </form>
    </div>

</div>
<!--    Start sendOrderToAlepayInstallment    -->

<div id="sendOrderToAlepayInstallment" class="modal fade" role="dialog">
    <iframe id="frame" scrolling="no" style="overflow: hidden;height: 100%;width: 100%;border: none;"></iframe>
</div><!-- /.row -->


<!--    End sendOrderToAlepayInstallment    -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    var iporn = 16000000;
    $('#amount').val(iporn.toLocaleString('vi'));
    $('#totalItem').on('keyup', function () {
        if (typeof $('#amount').val() != 'undefined') {
            $('#amount').val((parseInt(iporn) * parseInt($('#totalItem').val())).toLocaleString('vi'));
        }
    });
    $('#sendInstallment').on('click', function () {
        $('#alert').html('Đang tải...');
        $.ajax({
            type: "POST",
            url: $("#formSubmit").prop('action'),
            data: $("#formSubmit").serialize(), // serializes the form's elements.
            success: function (data) {
                console.log(data.error);
                if (data.error != 'OK') {
                    $('#alert').html('<div class="alert alert-danger">' + data.message + '</div>');
                    return false;
                } else {
                    $('#frame').prop('src', data.data);
                    $('#sendOrderToAlepayInstallment').modal('show');
                    $('#alert').html('');
                }

            }
        });
    });
    $('#frame').on('load', function () {
        this.style.height = this.contentDocument.body.scrollHeight + 'px';
    });
</script>
</body>
</html>

