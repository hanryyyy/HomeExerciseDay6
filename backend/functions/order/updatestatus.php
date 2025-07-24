<?php
session_start();
include_once(__DIR__ . '/../../../dbconnect.php');

// Lấy ID đơn hàng
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: manageorder.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];
    $allowed = ['pending', 'shipped', 'delivered'];

    if (in_array($newStatus, $allowed)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $newStatus, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manageorder.php");
        exit();
    } else {
        $error = "Trạng thái không hợp lệ.";
    }
}

// Lấy trạng thái hiện tại của đơn hàng
$sql = "SELECT status FROM orders WHERE id = $id";
$result = $conn->query($sql);
$order = $result->fetch_assoc();
$currentStatus = $order['status'];
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật trạng thái</title>
    <?php include_once(__DIR__ . '/../../layouts/styles.php'); ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>
    <div class="container mt-5">
        <h3>Cập nhật trạng thái đơn hàng <?= $id ?></h3>

        <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select class="form-control" name="status" id="status">
                    <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="shipped" <?= $currentStatus === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="delivered" <?= $currentStatus === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                </select>
            </div>
            <div class="mb-4"></div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="manageorder.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>
</body>

</html>