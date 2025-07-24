<?php
session_start();

// ✅ Chỉ cho admin truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /demoshop/frontend/pages/login.php?redirect=admin");
    exit();
}

// Kết nối DB
include_once(__DIR__ . '/../../../dbconnect.php');

// Lấy ID đơn hàng từ URL
$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($orderId <= 0) {
    echo "Mã đơn hàng không hợp lệ.";
    exit();
}

// Lấy thông tin đơn hàng
$sqlOrder = "SELECT o.*, u.username FROM orders o
             JOIN users u ON o.user_id = u.id
             WHERE o.id = $orderId";
$resultOrder = $conn->query($sqlOrder);
$order = $resultOrder->fetch_assoc();
if (!$order) {
    echo "Đơn hàng không tồn tại.";
    exit();
}

// Lấy danh sách sản phẩm trong đơn hàng
$sqlItems = "SELECT oi.*, p.name, p.image_url
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = $orderId";
$resultItems = $conn->query($sqlItems);
$items = [];
while ($row = $resultItems->fetch_assoc()) {
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng <?= $orderId ?></title>
    <?php include_once(__DIR__ . '/../../layouts/styles.php'); ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>

    <div class="container mt-5">
        <h3>Đơn hàng <?= $orderId ?> - Người đặt: <?= htmlspecialchars($order['username']) ?></h3>
        <p><strong>Ngày đặt:</strong> <?= $order['ordered_at'] ?></p>
        <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
        <p><strong>Trạng thái:</strong> <?= $order['status'] ?></p>

        <hr>

        <h4>Sản phẩm trong đơn hàng:</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($items as $item):
                    $subtotal = $item['quantity'] * $item['price_at_time'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><img src="/demoshop/assets/<?= $item['image_url'] ?>" style="width: 80px;"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price_at_time'], 0, ',', '.') ?> đ</td>
                        <td><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h5 class="text-end">Tổng tiền: <strong><?= number_format($total, 0, ',', '.') ?> đ</strong></h5>
        <a href="manageorder.php" class="btn btn-secondary mt-3">← Quay lại danh sách đơn hàng</a>
        <div class="mb-4"></div>
    </div>

    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>
</body>

</html>