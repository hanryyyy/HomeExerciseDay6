<?php
include_once(__DIR__ . '/../../dbconnect.php');

// Tổng số sản phẩm
$productCount = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

// Tổng số khách hàng
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'")->fetch_assoc()['total'];

// Tổng số đơn hàng
$orderCount = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];

echo json_encode([
    'products' => $productCount,
    'users' => $userCount,
    'orders' => $orderCount,
    'feedback' => $feedbackCount
]);
