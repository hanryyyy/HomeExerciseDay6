<?php
session_start();
include_once(__DIR__ . '/../../../dbconnect.php');

// Lấy danh sách đơn hàng
$sql = "SELECT o.*, u.username FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.ordered_at DESC";
$result = $conn->query($sql);
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>

    <?php include_once(__DIR__ . '/../../layouts/styles.php'); ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>

    <div class="container mt-4">
        <h2>Quản lý đơn hàng</h2>
        <a href="/demoshop/backend/pages/dashboard.php" class="btn btn-dark">
            ← Quay về Dashboard
        </a>

        <div class="mb-4"></div>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Người đặt</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Địa chỉ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['username']) ?></td>
                        <td><?= number_format($order['total_amount']) ?> đ</td>
                        <td><?= $order['ordered_at'] ?></td>
                        <td><?= ucfirst($order['status']) ?></td>
                        <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                        <td>
                            <a href="orderdetail.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Chi tiết</a>
                            <a href="updatestatus.php?id=<?= $order['id'] ?>" class="btn btn-warning btn-sm">Cập nhật trạng
                                thái</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>
</body>

</html>