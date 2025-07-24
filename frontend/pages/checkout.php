<?php
session_start();
include_once(__DIR__ . '/../../dbconnect.php');

$cart = $_SESSION['cart'] ?? [];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        $error = "Bạn cần đăng nhập để thanh toán.";
    } elseif (empty($name) || empty($phone) || empty($address)) {
        $error = "Vui lòng điền đầy đủ thông tin nhận hàng.";
    } elseif (empty($cart)) {
        $error = "Giỏ hàng của bạn đang trống.";
    } else {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, ordered_at, shipping_address) VALUES (?, ?, 'Pending', NOW(), ?)");
        $stmt->bind_param("ids", $user_id, $total, $address);
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_time) VALUES (?, ?, ?, ?)");
            foreach ($cart as $item) {
                $itemStmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                $itemStmt->execute();
            }

            $success = "Đặt hàng thành công! Cảm ơn bạn đã mua hàng.";
            unset($_SESSION['cart']);
        } else {
            $error = "Có lỗi xảy ra khi lưu đơn hàng.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
    <style>
        body {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <?php include_once(__DIR__ . '/../layouts/partials/header.php'); ?>

    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4">Thông tin thanh toán</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Họ tên</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ giao hàng</label>
                <textarea class="form-control" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Xác nhận đặt hàng</button>
            <a href="/demoshop/frontend/pages/viewCart.php" class="btn btn-secondary">Quay lại giỏ hàng</a>
        </form>
    </div>

    <?php include_once(__DIR__ . '/../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>
</body>

</html>