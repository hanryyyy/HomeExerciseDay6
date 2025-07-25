<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /demoshop/frontend/pages/login.php?redirect=checkout");
    exit();
}
include_once(__DIR__ . '/../../dbconnect.php');

// Lấy giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];
$user_id = $_SESSION['user_id'] ?? null;

$success = '';
$error = '';

// Nếu gửi form đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (!$user_id) {
        $error = "Bạn cần đăng nhập để đặt hàng.";
    } elseif (empty($name) || empty($phone) || empty($address)) {
        $error = "Vui lòng nhập đầy đủ thông tin nhận hàng.";
    } elseif (empty($cart)) {
        $error = "Giỏ hàng đang trống.";
    } else {
        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Thêm đơn hàng vào bảng orders
        $sqlOrder = "INSERT INTO orders (user_id, total_amount, status, ordered_at, shipping_address) 
                     VALUES (?, ?, 'Pending', NOW(), ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param("ids", $user_id, $total, $address);

        if ($stmtOrder->execute()) {
            $order_id = $stmtOrder->insert_id;

            // Lưu chi tiết từng sản phẩm vào bảng order_items
            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price_at_time) VALUES (?, ?, ?, ?)";
            $stmtItem = $conn->prepare($sqlItem);

            foreach ($cart as $item) {
                $stmtItem->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                $stmtItem->execute();
            }

            $success = "Đặt hàng thành công!";
            unset($_SESSION['cart']);
        } else {
            $error = "Đặt hàng thất bại. Vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../layouts/partials/header.php'); ?>

    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4">Thông tin thanh toán</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label for="name" class="form-label">Họ tên người nhận</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ giao hàng</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Xác nhận đặt hàng</button>
            <a href="viewCart.php" class="btn btn-secondary">Quay lại giỏ hàng</a>
        </form>
    </div>

    <?php include_once(__DIR__ . '/../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>
</body>

</html>