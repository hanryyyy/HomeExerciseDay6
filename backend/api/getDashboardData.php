<?php
include_once(__DIR__ . '/../../dbconnect.php');

// Đếm tổng số sản phẩm
$productSql = "SELECT COUNT(*) AS count FROM products";
$productCount = $conn->query($productSql)->fetch_assoc()['count'];

// Đếm tổng số khách hàng
$userSql = "SELECT COUNT(*) AS count FROM users";
$userCount = $conn->query($userSql)->fetch_assoc()['count'];

// Đếm tổng số đơn hàng
$orderSql = "SELECT COUNT(*) AS count FROM orders";
$orderCount = $conn->query($orderSql)->fetch_assoc()['count'];


echo json_encode([
    'products' => $productCount,
    'users' => $userCount,
    'orders' => $orderCount,
]);
