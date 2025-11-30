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

    <header
        style="padding: 20px 40px; background: rgba(26, 26, 46, 0.7); color: #fff; font-family: Arial, Helvetica, sans-serif; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
        <h1
            style="font-size: 32px; font-style: normal; margin: 0; font-weight: 900; letter-spacing: 2px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); color: #00d4ff;">
            JDM WORLD</h1>

        <nav style="display: flex; gap: 15px; align-items: center;">
            <a href="/index.php"
                style="color:white; font-weight: bold; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: all 0.3s ease; background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3);"
                onmouseover="this.style.backgroundColor='#00d4ff'; this.style.color='#000';"
                onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='white';">Trang chủ</a>

            <?php if (!isset($_SESSION["user_id"])): ?>
            <a href="/login.php"
                style="color:white; font-weight: bold; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: all 0.3s ease; background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3);"
                onmouseover="this.style.backgroundColor='#00d4ff'; this.style.color='#000';"
                onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='white';">Đăng nhập</a>
            <a href="/register.php"
                style="color:white; font-weight: bold; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: all 0.3s ease; background-color: #00d4ff; color: #000; border: 1px solid #00d4ff;"
                onmouseover="this.style.opacity='0.8';" onmouseout="this.style.opacity='1';">Đăng ký</a>
            <?php else: ?>
            <a href="/dashboard.php"
                style="color:white; font-weight: bold; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: all 0.3s ease; background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3);"
                onmouseover="this.style.backgroundColor='#00d4ff'; this.style.color='#000';"
                onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='white';">Trang chủ</a>
            <a href="/logout.php"
                style="color:white; font-weight: bold; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: all 0.3s ease; background-color: #ff4757; border: 1px solid #ff4757;"
                onmouseover="this.style.opacity='0.8';" onmouseout="this.style.opacity='1';">Đăng xuất</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>