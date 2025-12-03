<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>jdm world</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <header class="transparent-header">
        <h1 class="brand-title">JDM WORLD</h1>

        <nav style="display: flex; gap: 15px; align-items: center;">


            <?php if (!isset($_SESSION["user_id"])): ?>
            <a href="/index.php" class="nav-btn">Trang chủ</a>
            <a href="/login.php" class="nav-btn">Đăng nhập</a>
            <a href="/register.php" class="nav-btn primary-cta">Đăng ký</a>
            <?php else: ?>
            <a href="admin_dashboard.php" class="nav-btn">Trang chủ</a>
            <a href="/logout.php" class="nav-btn" style="background: var(--danger); border-color: var(--danger);">Đăng
                xuất</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>