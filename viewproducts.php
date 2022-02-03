<!doctype html>
<html lang="en">

<head>
  <?php include_once 'header.php'; ?>
  <title><?php echo "คลังสินค้า | " . $title; ?></title>
</head>

<body>
  <?php include_once 'navbar.php'; ?>
  
  <div class="h-auto d-flex justify-content-center align-items-center">
    <table class="table table-primary w-75">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">ชื่อสินค้า</th>
          <th scope="col">ผู้ผลิต</th>
          <th scope="col">จำนวน</th>
          <th scope="col">ราคา</th>
          <th scope="col">วันที่สั่งสินค้า</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php
        include "conn.php";
        $SQL = "SELECT * FROM `products`";
        if ($result = $conn->query($SQL)) {
          while (($item = $result->fetch_assoc())) {
            echo "<tr>";
            echo "<th>" . $item['ProductID'] . "</th>";
            echo "<td>" . $item['ProductName'] . "</td>";

            $where = $conn->query("SELECT `SupplierName` FROM `suppliers` WHERE `SupplierID` = " . $item['SupplierID']);
            while ($row = $where->fetch_assoc()) {
              echo "<td>" . $row["SupplierName"] . "</td>";
            }

            echo "<td>" . $item['Unit'] . "</td>";
            echo "<td>" . $item['Price'] . "</td>";
            echo "<td>" . $item['ProductDate'] . "</td>";

            echo "<td>" .
              "<form action='viewproducts.php' method='get'>" .
              "<input type='hidden' name='id' value='" . $item['ProductID'] . "'>" .
              "<button type='submit' class='btn btn-warning' id='btn-edit' name='btn' value='edit'>แก้ไข</button>" .
              "</form>" .
              "</td>";

            echo "</tr>";
          }
          $result->free_result();
        }
        echo "<tr><th></<th><td></td><td></td><td></td><td></td><td></td><td>" .
          "<form action='viewproducts.php' method='post'>" .
          "<button type='submit' class='btn btn-primary' name='btn' value='new' id='btn-view'>เพิ่มสินค้า</button>" .
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
      $ProductID = $_GET['id'];
      $title = "แก้ไขข้อมูลสินค้า";

      $sql = "SELECT * FROM `products` WHERE ProductID = " . $ProductID;
      $where = $conn->query($sql);
      $item = $where->fetch_assoc();

      $sql_where = $conn->query("SELECT `SupplierName` FROM `suppliers` WHERE `SupplierID` = " . $item['SupplierID']);
      $name = $sql_where->fetch_assoc();

      $content = '
          <form action="viewproducts.php" method="post">
            <div class="modal-body">
              <div class="mb-3">
                <label for="ProductName" class="col-form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" id="ProductName" name="ProductName" value="' . $item['ProductName'] . '">
              </div>
              <div class="mb-3">
                <label for="SupplierName" class="col-form-label">ผู้ผลิต</label>
                <input type="text" class="form-control" id="SupplierName" name="SupplierName" value="' . $name['SupplierName'] . '" disabled> 
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
              <button type="submit" name="id" value="' . $item['ProductID'] . '" class="btn btn-danger">บันทึก</button>
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
    }
  } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btn']) and $_POST['btn'] === 'new') {
      $title = "เพิ่มสินค้า";
      $content = '
          <form action="viewproducts.php" method="post">
            <div class="modal-body">
              <div class="mb-3">
                <label for="ProductName" class="col-form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" id="ProductName" name="ProductName">
              </div>
              <div class="mb-3">
                <label for="SupplierName" class="col-form-label">ผู้ผลิต</label>
                <input type="text" class="form-control" id="SupplierName" name="SupplierName"> 
              </div>
              <div class="mb-3">
                <label for="Unit" class="col-form-label">จำนวน</label>
                <input type="text" class="form-control" id="Unit" name="Unit">
              </div>
              <div class="mb-3">
                <label for="Price" class="col-form-label">ราคา</label>
                <input type="text" class="form-control" id="Price" name="Price">
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
      </script>
      ";
    } elseif (isset($_POST['btn']) and $_POST['btn'] === 'add') {

      $SupplierName = $conn->real_escape_string($_POST['SupplierName']);

      $where = $conn->query("SELECT `SupplierID` FROM `suppliers` WHERE `SupplierName` = '" . $SupplierName . "'");

      if ($where->num_rows > 0) {
        while ($row = $where->fetch_assoc()) {
          $SupplierID = $row["SupplierID"];
        }
      } else {
        $sql = "INSERT INTO `suppliers` (`SupplierName`) VALUES ('$SupplierName')";
        if ($conn->query($sql) === TRUE) {
          $where_SupplierID = $conn->query("SELECT `SupplierID` FROM `suppliers` WHERE `SupplierName` = '" . $SupplierName . "'");
          $row = $where_SupplierID->fetch_assoc();
          $SupplierID = $row["SupplierID"];
        } else {
          alert("เพิ่มผู้ผลิตไม่สำเร็จ");
        }
      }


      $SQL = "INSERT INTO `products`(`ProductName`, `SupplierID`, `Unit`, `Price`) VALUES "
        . sprintf(
          "('%s', '%s', '%s', '%s')",
          $conn->real_escape_string($_POST['ProductName']),
          $conn->real_escape_string($SupplierID),
          $conn->real_escape_string($_POST['Unit']),
          $conn->real_escape_string($_POST['Price'])
        );

      if ($conn->query($SQL) === TRUE) {
        alert("เพิ่มสำเร็จ");
      } else {
        alert("เพิ่มไม่สำเร็จ");
      }
      echo "<script>location.href = location.href;</script>";
    } else {
      $ProductID = $_POST['id'];

      $ProductName = $conn->real_escape_string($_POST['ProductName']);
      $Unit = $conn->real_escape_string($_POST['Unit']);
      $Price = $conn->real_escape_string($_POST['Price']);

      $sql_update = "UPDATE `products` SET `ProductName`='$ProductName', `Unit`='$Unit', `Price`='$Price' WHERE `ProductID` = " . $ProductID;

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