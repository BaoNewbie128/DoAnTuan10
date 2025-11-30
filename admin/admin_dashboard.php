<?php include __DIR__ . "/../includes/auth_check.php";
    include __DIR__ . "/../config/db.php";
    $sql = "SELECT COUNT(*) AS total_products FROM products";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_products = $row['total_products'];
    $sql2 = "SELECT COUNT(*) AS total_users FROM users WHERE role='customer'";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $total_users = $row2['total_users'];
?>

<h2 class="text-white">Chào <?php echo $_SESSION["username"]; ?></h2>
<p class="text-white">Vai trò: <?php echo $_SESSION["role"]; ?></p>
<div class="flex justify-between items-center mb-6">
    <div>
        <button class="px-4 py-2 bg-red-600 text-white rounded shadow "><a href="/logout.php"
                class="text-decoration-none">Đăng
                xuất</a></button>
    </div>
</div>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trang Chủ - Admin - JDM World</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"" rel="
        stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/../assets/css/style.css">
</head>

<body style="background: url('/../images/BackGround.jpg');">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white p-5 space-y-4">
            <nav class="space-y-2">
                <a href="admin_dashboard.php" class="block p-2 rounded hover:bg-gray-700">Trang chủ</a>
                <a href="admin_dashboard.php?view=products" class="block p-2 rounded hover:bg-gray-700">Quản lý sản
                    phẩm</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-700">Đơn hàng</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-700">Khách hàng</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-700">Đánh giá</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-700">Cài đặt</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <?php 
                if(isset($_GET["view"]) && $_GET["view"] === "products") {
                    include __DIR__ . "/product_management.php";
                }else {
            ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-5 rounded shadow text-center">
                    <h3 class="text-lg font-semibold">Tổng sản phẩm</h3>
                    <p class="text-3xl font-bold text-red-600"><?php echo $total_products; ?></p>
                </div>
                <div class="bg-white p-5 rounded shadow text-center">
                    <h3 class="text-lg font-semibold">Đơn hàng mới</h3>
                    <p class="text-3xl font-bold text-blue-600">12</p>
                </div>
                <div class="bg-white p-5 rounded shadow text-center">
                    <h3 class="text-lg font-semibold">Khách hàng</h3>
                    <p class="text-3xl font-bold text-green-600"><?php echo $total_users; ?></p>
                </div>
            </div>
            <?php  
                }
             ?>

        </main>
    </div>
</body>

</html>