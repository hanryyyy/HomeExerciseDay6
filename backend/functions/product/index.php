<!-- Nhúng file cấu hình để xác định được Tên và Tiêu đề của trang hiện tại người dùng đang truy cập -->
<?php include_once(__DIR__ . '/../../layouts/config.php'); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
    <?php include_once(__DIR__ . '/../../layouts/head.php'); ?>

</head>

<body class="d-flex flex-column h-100">
    <!-- header -->
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>
    <!-- end header -->

    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- end sidebar -->

            <main role="main" class="col-md-10 ml-sm-auto px-4 mb-2">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Product List</h1>
                </div>

                <!-- Block content -->
                <?php
                // Access to db to get data
                // 1. Include configuration file connecting to database, initial connection $conn
                include_once(__DIR__ . '/../../../dbconnect.php');

                // Số sản phẩm trên mỗi trang
                $limit = 10;

                // Trang hiện tại
                $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

                // Tính offset
                $offset = ($page - 1) * $limit;

                // Đếm tổng sản phẩm
                $totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
                $totalRow = $totalResult->fetch_assoc();
                $totalProducts = $totalRow['total'];
                $totalPages = ceil($totalProducts / $limit);

                // Truy vấn sản phẩm giới hạn theo trang
                $sql = "SELECT id, name, price, stock_quantity, image_url, category FROM products ORDER BY id DESC LIMIT $limit OFFSET $offset";
                $result = $conn->query($sql);

                // Lấy dữ liệu sản phẩm
                $prods = [];
                while ($row = $result->fetch_array(MYSQLI_NUM)) {
                    $prods[] = $row;
                }

                $result->free_result();
                $conn->close();
                // print_r($prods); die();
                ?>

                <a href="create.php" class="btn btn-primary">Create New</a>
                <table id="tblDanhSach" class="table table-bordered table-hover table-sm table-responsive mt-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prods as $item) : ?>
                            <tr>
                                <td><?= $item[0] ?></td>
                                <td><?= $item[1] ?></td>
                                <td><?= $item[2] ?></td>
                                <td><?= $item[3] ?></td>
                                <td>
                                    <img src="/demoshop/assets/<?= $item[4] ?>" alt="" style="width:200px;height:auto;" />
                                </td>
                                <td><?= $item[5] ?></td>
                                <td>
                                    <a href="update.php?id=<?= $item['0'] ?>" class="btn btn-warning">Update</a>
                                    <a href="delete.php?id=<?= $item['0'] ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- PHÂN TRANG -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                        </li>
                    </ul>
                </nav>

                <!-- End block content -->
            </main>
        </div>
    </div>

    <!-- footer -->
    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <!-- end footer -->

    <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
    <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>

</body>

</html>