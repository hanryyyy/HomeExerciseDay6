<?php
session_start();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Vui lòng điền đầy đủ tất cả các trường.";
    } else {
        // Giả sử gửi email hoặc lưu vào DB ở đây
        $success = "Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ</title>
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../layouts/partials/header.php'); ?>

    <main class="container mt-5 mb-5">
        <h2 class="mb-4 text-center">Liên hệ với chúng tôi</h2>

        <?php if (!empty($success)) : ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
        <?php elseif (!empty($error)) : ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="col-md-8 offset-md-2">
            <div class="form-group mb-3">
                <label for="name">Họ và tên:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập họ và tên..." required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Địa chỉ Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com"
                    required>
            </div>
            <div class="form-group mb-3">
                <label for="subject">Chủ đề:</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Tiêu đề liên hệ"
                    required>
            </div>
            <div class="form-group mb-4">
                <label for="message">Nội dung:</label>
                <textarea class="form-control" id="message" name="message" rows="5"
                    placeholder="Nhập nội dung liên hệ..." required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">Gửi</button>
            </div>
        </form>
    </main>

    <?php include_once(__DIR__ . '/../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>
</body>

</html>