&nbsp;
<div class="h-2 d-flex justify-content-center align-items-center">

    <h4>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">หน้าแรก</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vieworders.php">ใบสั่งสินค้า</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="viewproducts.php">คลังสินค้า</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="viewsuppliers.php">รายชื่อผู้ผลิต</a>
            </li>
        </ul>
    </h4>

</div>


<script>
    document.querySelector('.active').classList.remove('active')

    const pathname = location.pathname
    const path = pathname.split('/')[2]
    switch (path) {
        case 'vieworders.php':
            document.querySelectorAll('.nav-link')[1].classList.add('active')
            break;
        case 'viewproducts.php':
            document.querySelectorAll('.nav-link')[2].classList.add('active')
            break;
        case 'viewsuppliers.php':
            document.querySelectorAll('.nav-link')[3].classList.add('active')
            break;
        default:
            document.querySelectorAll('.nav-link')[0].classList.add('active')
            break;
    }
</script>