<?php include "conn.php"; ?>

<!doctype html>
<html lang="en">

<head>
    <?php include_once 'header.php'; ?>
    <title><?php echo "ใบสั่งซื้อสินค้า | " . $title; ?></title>
</head>

<body>
    <?php include_once 'navbar.php'; ?>
    
    <div class="h-auto d-flex justify-content-center align-items-center">
        <table class="table table-primary w-50">
            <thead>
                <tr>
                    <th scope="col">เลขที่สั่งซื้อ</th>
                    <th scope="col">วันที่สั่งซื้อ</th>
                    <th scope="col">สถานะ</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php

                $SQL = "SELECT DISTINCT `OrderDetailID`, `OrderDate`, `Status` FROM `orders`;";
                if ($result = $conn->query($SQL)) {

                    while (($item = $result->fetch_assoc())) {

                        $item["Status"] ? $Status = "ดำเนินการแล้ว" : $Status = "รอดำเนินการ";

                        echo "<tr>";
                        echo "<th>" . $item["OrderDetailID"] . "</th>";
                        echo "<td>" . $item["OrderDate"] . "</td>";
                        echo "<td>" . $Status . "</td>";
                        echo "<td>" .
                            "<form action='vieworders.php' method='get'>" .
                            "<input type='hidden' name='id' value='" . $item['OrderDetailID'] . "'>" .
                            "<button type='submit' class='btn btn-info' id='btn-view' name='btn' value='view'>ดูรายละเอียด</button>" .
                            "</form>" .
                            "</td>";
                        echo "</tr>";
                    }
                    $result->free_result();
                }
                echo "<tr><th></<th><td></td><td></td><td>" .
                    "<form action='vieworders.php' method='post'>" .
                    "<button type='submit' class='btn btn-primary' name='btn' value='new' id='btn-view'>เพิ่มใบสั่งซื้อ</button>" .
                    "</td></tr>" .
                    "</form>";

                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="view" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['id']) and isset($_GET['btn'])) {
            if ($_GET['btn'] === 'view') {

                $OrderDetailID = $_GET['id'];
                $title = "รายละเอียดการสั่งซื้อ";
                $body = "";

                $OrderDate = "";
                $sum = 0;

                $where_OrderDetailID = $conn->query("SELECT `OrderID`, `OrderDetailID`, `OrderDate`, `ProductName`, `SupplierName`, `Unit`, `Price` FROM `orders` WHERE OrderDetailID = " . $OrderDetailID);
                if ($where_OrderDetailID) {
                    while ($orders = $where_OrderDetailID->fetch_assoc()) {
                        $body .= "<tr>";
                        $body .= "<th>" . $orders['OrderDetailID'] . "</th>";
                        $body .= "<td>" . $orders['OrderDate'] . "</td>";
                        $body .= "<td>" . $orders['ProductName'] . "</td>";
                        $body .= "<td class=\"text-break\">" . $orders["SupplierName"] . "</td>";
                        $body .= "<td>" . $orders['Unit'] . "</td>";
                        $body .= "<td>" . $orders['Price'] . "</td>";

                        $body .= "<td>";
                        $body .= "
                            <form action='vieworders.php' method='get'>
                            <input type='hidden' name='id' value='" . $orders['OrderID'] . "'>
                            <button type='submit' class='btn btn-warning' id='btn-edit' name='btn' value='edit'>แก้ไข</button>
                            <button type='submit' class='btn btn-danger' id='btn-edit' name='btn' value='delete'>ลบ</button>
                            </form>
                        ";
                        $body .= "</td>";

                        $body .= "</tr>";

                        $OrderDate = $orders['OrderDate'];
                        $sum += $orders['Price'];
                    }
                }


                $body .= "<tr><th></<th><td></td><td></td><td></td><td></td><td></td>";
                $body .= "
                    <td>
                        <form action='vieworders.php' method='post'>
                            <input type='hidden' name='id' value='" . $OrderDetailID . "'>
                            <input type='hidden' name='date' value='" . $OrderDate . "'>
                            <button type='submit' class='btn btn-primary' name='btn' value='new_list' id='btn-view'>เพิ่มรายการสินค้า</button>
                        </form>
                    </td>
                ";
                $body .= "<td></td></tr>";
                $body .= "<tr><th></<th><td></td><td></td><td></td><td></td><td>รวม $sum</td><td></td></tr>";
                $content = '
                    <div class="modal-body">
                    <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">เลขที่สั่งซื้อ</th>
                        <th scope="col">วันที่สั่งซื้อ</th>
                        <th scope="col">ชื่อสินค้า</th>
                        <th scope="col">ชื่อผู้ผลิต</th>
                        <th scope="col">จำนวน</th>
                        <th scope="col">ราคา</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                        ' . $body . '
                    </tbody>
                    </table>
                    </div>
                    ';

                echo "
                    <script>
                        const title = document.getElementById('ModalLabel')
                        const content = document.getElementById('content')
            
            
                        const myModal = new bootstrap.Modal(document.getElementById('view'), {
                        keyboard: false
                        })
                        title.innerHTML = '$title'
                        content.innerHTML += `$content`
                        myModal.show()
                    </script>
                    ";
            } elseif ($_GET['btn'] === 'edit') {
                $OrderID = $_GET['id'];
                $title = "แก้ไขข้อมูลการสั่งซื้อ";

                $sql = "SELECT * FROM `orders` WHERE OrderID = " . $OrderID;
                $where = $conn->query($sql);
                $item = $where->fetch_assoc();

                $content = '
                    <form action="vieworders.php" method="post">
                        <div class="modal-body">
                        <div class="mb-3">
                            <label for="ProductName" class="col-form-label">ชื่อสินค้า</label>
                            <input type="text" class="form-control" id="ProductName" name="ProductName" value="' . $item['ProductName'] . '">
                        </div>
                        <div class="mb-3">
                            <label for="SupplierName" class="col-form-label">ผู้ผลิต</label>
                            <input type="text" class="form-control" id="SupplierName" name="SupplierName" value="' . $item['SupplierName'] . '"> 
                        </div>
                        <div class="mb-3">
                            <label for="Unit" class="col-form-label">จำนวน</label>
                            <input type="text" class="form-control" id="Unit" name="Unit" value="' . $item['Unit'] . '">
                        </div>
                        <div class="mb-3">
                            <label for="Price" class="col-form-label">ราคา</label>
                            <input type="text" class="form-control" id="Price" name="Price" value="' . $item['Price'] . '">
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" name="id" value="' . $item['OrderID'] . '" class="btn btn-danger">บันทึก</button>
                        </div>
                    </form>
                    ';

                echo "
                <script>
                    const title = document.getElementById('ModalLabel')
                    const content = document.getElementById('content')


                    const myModal = new bootstrap.Modal(document.getElementById('view'), {
                    keyboard: false
                    })
                    title.innerHTML = '$title'
                    content.innerHTML += `$content`
                    myModal.show()
                </script>
                ";
            } elseif ($_GET['btn'] === 'delete') {

                $OrderID  = $_GET['id'];
                $sql_delete = "DELETE FROM `orders` WHERE OrderID = " . $OrderID;
                if ($conn->query($sql_delete) === TRUE) {
                    alert('ลบสำเร็จ');
                } else {
                    alert('ลบไม่สำเร็จ' . $conn->error);
                }
                echo "<script>location.href = location.pathname;</script>";
            }
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['btn']) and $_POST['btn'] === 'new') {
            $title = "ใบสั่งซื้อสินค้า";
            $content = '
                <form action="vieworders.php" method="post">
                    <div class="modal-body" id="body">
                    <div class="mb-3">
                        <label for="ProductName" class="col-form-label">ชื่อสินค้า</label>
                        <input type="text" class="form-control" id="ProductName" name="ProductName[]">
                    </div>
                    <div class="mb-3">
                        <label for="SupplierName" class="col-form-label">ชื่อผู้ผลิต</label>
                        <input type="text" class="form-control" id="SupplierName" name="SupplierName[]"> 
                    </div>
                    <div class="mb-3">
                        <label for="Unit" class="col-form-label">จำนวน</label>
                        <input type="text" class="form-control" id="Unit" name="Unit[]">
                    </div>
                    <div class="mb-3">
                        <label for="Price" class="col-form-label">ราคา</label>
                        <input type="text" class="form-control" id="Price" name="Price[]">
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-info" onclick="addInput()">เพิ่มช่อง</button>
                    <button type="submit" name="btn" value="add" class="btn btn-danger">เพิ่ม</button>
                    </div>
                </form>
                ';
            echo "
                <script>
                    const title = document.getElementById('ModalLabel')
                    const content = document.getElementById('content')


                    const myModal = new bootstrap.Modal(document.getElementById('view'), {
                    keyboard: false
                    })
                    title.innerHTML = '$title'
                    content.innerHTML += `$content`
                    myModal.show()
                </script>
                ";
        } elseif (isset($_POST['btn']) and $_POST['btn'] === 'add') {
            $ProductName = $_POST['ProductName'];
            $SupplierName = $_POST['SupplierName'];
            $Unit = $_POST['Unit'];
            $Price = $_POST['Price'];

            $where_max = $conn->query("SELECT MAX(`OrderDetailID`) as MAX FROM `orders`");
            $max = $where_max->fetch_assoc()['MAX'] + 1;

            $sql = "INSERT INTO `orders`(`OrderDetailID`, `ProductName`, `SupplierName`, `Unit`, `Price`) VALUES ";
            $value = array();

            for ($i = 0; $i < count($ProductName); $i++) {
                array_push($value, "('$max','$ProductName[$i]','$SupplierName[$i]','$Unit[$i]','$Price[$i]')");
            }

            $values = join(',', $value);
            $SQL = $sql . $values;
            if ($conn->query($SQL) === TRUE) {
                alert("เพิ่มสำเร็จ");
            } else {
                alert("เพิ่มไม่สำเร็จ");
            }
            echo "<script>location.href = location.href;</script>";
        } elseif (isset($_POST['btn']) and $_POST['btn'] === 'new_list') {
            $OrderDetailID = $_POST['id'];
            $OrderDate = $_POST['date'];
            $title = "เพิ่มรายการสินค้า";
            $content = '
                <form action="vieworders.php" method="post">
                    <div class="modal-body" id="body">
                    <div class="mb-3">
                        <label for="OrderDetailID" class="col-form-label">เลขที่สั่งซื้อ</label>
                        <input type="hidden" name="id" value="' . $OrderDetailID . '">
                        <input type="hidden" name="date" value="' . $OrderDate . '">
                        <input type="text" class="form-control" id="OrderDetailID" value="' . $OrderDetailID . '" disabled>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="ProductName" class="col-form-label">ชื่อสินค้า</label>
                        <input type="text" class="form-control" id="ProductName" name="ProductName[]">
                    </div>
                    <div class="mb-3">
                        <label for="SupplierName" class="col-form-label">ชื่อผู้ผลิต</label>
                        <input type="text" class="form-control" id="SupplierName" name="SupplierName[]"> 
                    </div>
                    <div class="mb-3">
                        <label for="Unit" class="col-form-label">จำนวน</label>
                        <input type="text" class="form-control" id="Unit" name="Unit[]">
                    </div>
                    <div class="mb-3">
                        <label for="Price" class="col-form-label">ราคา</label>
                        <input type="text" class="form-control" id="Price" name="Price[]">
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-info" onclick="addInput()">เพิ่มช่อง</button>
                    <button type="submit" name="btn" value="add_list" class="btn btn-danger">เพิ่ม</button>
                    </div>
                </form>
                ';
            echo "
                <script>
                    const title = document.getElementById('ModalLabel')
                    const content = document.getElementById('content')


                    const myModal = new bootstrap.Modal(document.getElementById('view'), {
                    keyboard: false
                    })
                    title.innerHTML = '$title'
                    content.innerHTML += `$content`
                    myModal.show()
                </script>
                ";
        } elseif (isset($_POST['btn']) and $_POST['btn'] === 'add_list') {
            
            $OrderDetailID = $_POST['id'];
            $OrderDate = $_POST['date'];
            
            $ProductName = $_POST['ProductName'];
            $SupplierName = $_POST['SupplierName'];
            $Unit = $_POST['Unit'];
            $Price = $_POST['Price'];

            $sql = "INSERT INTO `orders`(`OrderDetailID`, `OrderDate`, `ProductName`, `SupplierName`, `Unit`, `Price`) VALUES ";
            $value = array();

            for ($i = 0; $i < count($ProductName); $i++) {
                array_push($value, "('$OrderDetailID','$OrderDate','$ProductName[$i]','$SupplierName[$i]','$Unit[$i]','$Price[$i]')");
            }

            $values = join(',', $value);
            $SQL = $sql . $values;
            if ($conn->query($SQL) === TRUE) {
                alert("เพิ่มสำเร็จ");
            } else {
                alert("เพิ่มไม่สำเร็จ");
            }
            echo "<script>location.href = location.href;</script>";

        } else {
            $OrderID  = $_POST['id'];

            $ProductName = $conn->real_escape_string($_POST['ProductName']);
            $SupplierName = $conn->real_escape_string($_POST['SupplierName']);
            $Unit = $conn->real_escape_string($_POST['Unit']);
            $Price = $conn->real_escape_string($_POST['Price']);

            $sql_update = "UPDATE `orders` SET `ProductName`='$ProductName', `SupplierName`='$SupplierName', `Unit`='$Unit', `Price`='$Price' WHERE `OrderID` = " . $OrderID;

            if ($conn->query($sql_update) === TRUE) {
                alert('แก้ไขสำเร็จ');
            } else {
                alert('แก้ไขไม่สำเร็จ' . $conn->error);
            }
            echo "<script>location.href = location.href;</script>";
        }
    }
    $conn->close();
    ?>

    <script>
        const addInput = () => {
            const body = document.getElementById('body')
            const div = document.createElement('div')
            const form = `
                <hr>
                <div class="mb-3">
                    <label for="ProductName" class="col-form-label">ชื่อสินค้า</label>
                    <input type="text" class="form-control" id="ProductName" name="ProductName[]">
                </div>
                <div class="mb-3">
                    <label for="SupplierName" class="col-form-label">ชื่อผู้ผลิต</label>
                    <input type="text" class="form-control" id="SupplierName" name="SupplierName[]">
                </div>
                <div class="mb-3">
                    <label for="Unit" class="col-form-label">จำนวน</label>
                    <input type="text" class="form-control" id="Unit" name="Unit[]">
                </div>
                <div class="mb-3">
                    <label for="Price" class="col-form-label">ราคา</label>
                    <input type="text" class="form-control" id="Price" name="Price[]">
                </div>
            `
            div.innerHTML = form
            body.appendChild(div)


        }
    </script>


</body>

</html>