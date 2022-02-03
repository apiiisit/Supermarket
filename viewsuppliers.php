<!doctype html>
<html lang="en">

<head>
  <?php include_once 'header.php'; ?>
  <title><?php echo "รายชื่อผู้ผลิต | " . $title; ?></title>
</head>

<body>
  <?php include_once 'navbar.php'; ?>
  
  <div class="h-auto d-flex justify-content-center align-items-center">
    <table class="table table-primary w-75">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">ชื่อโรงงาน</th>
          <th scope="col">ที่อยู่</th>
          <th scope="col">รหัสไปรษณีย์</th>
          <th scope="col">เบอร์โทร</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php
        include "conn.php";
        $SQL = "SELECT `SupplierID`, `SupplierName`, `Address`, `PostalCode`, `Phone` FROM `suppliers`";
        if ($result = $conn->query($SQL)) {
          while (($item = $result->fetch_assoc())) {
            echo "<tr>";
            echo "<th>" . $item["SupplierID"] . "</th>";
            echo "<td>" . $item["SupplierName"] . "</td>";
            echo "<td class=\"text-break\">" . $item["Address"] . "</td>";
            echo "<td>" . $item["PostalCode"] . "</td>";
            echo "<td>" . $item["Phone"] . "</td>";
            echo "<td>" .
              "<form action='viewsuppliers.php' method='get'>" .
              "<input type='hidden' name='id' value='" . $item["SupplierID"] . "'>" .
              "<button type='submit' class='btn btn-info' name='btn' value='view' id='btn-view'>ดูสินค้า</button> " .
              "<button type='submit' class='btn btn-warning' name='btn' value='edit' id='btn-edit'>แก้ไข</button>" .
              "</form>" .
              "</td>";

            echo "</tr>";
          }
          $result->free_result();
        }
        echo "<tr><th></<th><td></td><td></td><td></td><td></td><td>" .
          "<form action='viewsuppliers.php' method='post'>" .
          "<button type='submit' class='btn btn-primary' name='btn' value='new' id='btn-view'>เพิ่มผู้ผลิต</button>" .
          "</td></tr>" .
          "</form>";


        ?>
      </tbody>
    </table>
  </div>


  <div class="modal fade" id="view" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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

      $_GET['btn'] === 'view' ? $title = 'รายการสินค้า' : $title = 'แก้ไขข้อมูลผู้ผลิต';
      $SupplierID = $_GET['id'];

      if ($title === 'รายการสินค้า') {
        $sql = "SELECT `ProductName`, `Unit`, `Price`, `ProductDate` FROM `products` WHERE SupplierID = " . $SupplierID;
        $body = "";

        if ($where = $conn->query($sql)) {
          while (($item = $where->fetch_assoc())) {
            $body .= "<tr>";
            $body .= "<th>" . $item['ProductName'] . "</th>";
            $body .= "<td>" . $item['Unit'] . "</td>";
            $body .= "<td>" . $item['Price'] . "</td>";
            $body .= "<td>" . $item['ProductDate'] . "</td>";
            $body .= "</tr>";
          }
        }

        $content = '
        <div class="modal-body">
        <table class="table">
        <thead>
          <tr>
            <th scope="col">ชื่อสินค้า</th>
            <th scope="col">จำนวน</th>
            <th scope="col">ราคา</th>
            <th scope="col">วันที่สั่งสินค้า</th>
          </tr>
        </thead>
        <tbody>
            ' . $body . '
        </tbody>
        </table>
        </div>
        
        ';
      } else {
        $sql = "SELECT `SupplierName`, `Address`, `PostalCode`, `Phone` FROM `suppliers` WHERE SupplierID = " . $SupplierID;
        $where = $conn->query($sql);
        $item = $where->fetch_assoc();

        $content = '
          <form action="viewsuppliers.php" method="post">
            <div class="modal-body">
              <div class="mb-3">
                <label for="SupplierName" class="col-form-label">ชื่อโรงงาน</label>
                <input type="text" class="form-control" id="SupplierName" name="SupplierName" value="' . $item['SupplierName'] . '">
              </div>
              <div class="mb-3">
                <label for="Address" class="col-form-label">ที่อยู่</label>
                <textarea class="form-control" id="Address" name="Address">' . $item['Address'] . '</textarea>
              </div>
              <div class="mb-3">
                <label for="PostalCode" class="col-form-label">รหัสไปรษณีย์</label>
                <input type="text" class="form-control" id="PostalCode" name="PostalCode" value="' . $item['PostalCode'] . '">
              </div>
              <div class="mb-3">
                <label for="Phone" class="col-form-label">เบอร์โทร</label>
                <input type="text" class="form-control" id="Phone" name="Phone" value="' . $item['Phone'] . '">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="submit" name="id" value="' . $SupplierID . '" class="btn btn-danger">บันทึก</button>
            </div>
          </form>
        ';
      }


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
    }
  } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btn']) and $_POST['btn'] === 'new') {
      $title = "เพิ่มผู้ผลิตสินค้า";
      $content = '
          <form action="viewsuppliers.php" method="post">
            <div class="modal-body">
              <div class="mb-3">
                <label for="SupplierName" class="col-form-label">ชื่อโรงงาน</label>
                <input type="text" class="form-control" id="SupplierName" name="SupplierName">
              </div>
              <div class="mb-3">
                <label for="Address" class="col-form-label">ที่อยู่</label>
                <textarea class="form-control" id="Address" name="Address"></textarea>
              </div>
              <div class="mb-3">
                <label for="PostalCode" class="col-form-label">รหัสไปรษณีย์</label>
                <input type="text" class="form-control" id="PostalCode" name="PostalCode"">
              </div>
              <div class="mb-3">
                <label for="Phone" class="col-form-label">เบอร์โทร</label>
                <input type="text" class="form-control" id="Phone" name="Phone">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
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
        </script>";
    } elseif (isset($_POST['btn']) and $_POST['btn'] === 'add') {
      $SQL = "INSERT INTO `suppliers` (`SupplierName`, `Address`, `PostalCode`, `Phone`) VALUES "
        . sprintf(
          "('%s', '%s', '%s', '%s')",
          $conn->real_escape_string($_POST['SupplierName']),
          $conn->real_escape_string($_POST['Address']),
          $conn->real_escape_string($_POST['PostalCode']),
          $conn->real_escape_string($_POST['Phone'])
        );

      if ($conn->query($SQL) === TRUE) {
        alert("เพิ่มสำเร็จ");
      } else {
        alert("เพิ่มไม่สำเร็จ");
      }
      echo "<script>location.href = location.href;</script>";
    } else {
      $SupplierID = $_POST['id'];

      $SupplierName = $conn->real_escape_string($_POST['SupplierName']);
      $Address = $conn->real_escape_string($_POST['Address']);
      $PostalCode = $conn->real_escape_string($_POST['PostalCode']);
      $Phone = $conn->real_escape_string($_POST['Phone']);

      $sql_update = "UPDATE `suppliers` SET `SupplierName`='$SupplierName', `Address`='$Address', `PostalCode`='$PostalCode', `Phone`='$Phone' WHERE `SupplierID` = " . $SupplierID;

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

</body>

</html>