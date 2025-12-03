<?php
session_start();
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = trim($_POST["login"]); // username hoặc email
    $password = $_POST["password"];
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            // Lưu session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            if ($user["role"] == "admin") {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }
            exit;
        }
    }

    $error = "Sai thông tin đăng nhập!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Đăng nhập</title>
</head>

<body class="bg-light" style="background: url('images/BackGround.jpg');">
    <div class="app-container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Đăng nhập</h2>

                <?php if (!empty($error)):?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="login" class="form-label">Email hoặc Username</label>
                        <input type="text" class="form-control" id="login" name="login"
                            placeholder="Nhập email hoặc username" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Nhập mật khẩu" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Đăng nhập</button>
                    <a href="index.php" class="btn btn-outline-secondary w-100 mt-3 py-2">← Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>