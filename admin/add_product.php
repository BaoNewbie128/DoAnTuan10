<?php
    include __DIR__ . "/../config/db.php";
    $success = "";
    $error = "";
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $brand = $conn->real_escape_string($_POST['brand']);
        $model = $conn->real_escape_string($_POST['model']);
        $scale = $conn->real_escape_string($_POST['scale']);
        $price = floatval($_POST['price']);
        $stock = $conn->real_escape_string($_POST['stock']);
        $description = $conn->real_escape_string($_POST['description']);
        $image_name = "";
        if(!empty($_FILES['image'])){
          $image_name = time() . "_" . basename($_FILES['image']['name']);
          move_uploaded_file($_FILES['image']["tmp_name"], __DIR__ . "/../images/" . $image_name);
        } 
        $sql = "INSERT INTO products (brand, model, scale, price, stock, description, image) VALUES ('$brand', '$model', '$scale', $price, '$stock', '$description', '$image_name')";
          if($conn->query($sql) === TRUE){
              $success = "Thêm sản phẩm thành công!";
          } else {
              $error_message = "Lỗi: " . $sql . "<br>" . $conn->error;
          }
    header("Location: admin_dashboard.php?view=products");
    exit; 
    }
    
?>
<a href="admin_dashboard.php?view=products" class="btn btn-secondary">Quay lại</a>
<h2 style="color: blue">Thêm sản phẩm mới</h2>
<?php if(!empty($success)): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if(!empty($error_message)): ?>
<div class="alert alert-danger"><?= $error_message ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Hãng</label><br />
        <input type="text" name="brand" class="form-control" required><br />
        <label class="form-label">Mẫu</label><br />
        <input type="text" name="model" class="form-control" required><br />
        <label class="form-label">Tỉ lệ</label><br />
        <input type="text" name="scale" class="form-control" required><br />
        <label class="form-label">Giá</label><br />
        <input type="number" name="price" step="0.01" class="form-control" required><br />
        <label class="form-label">Số lượng</label><br />
        <input type="number" name="stock" class="form-control" required><br />
        <label class="form-label">Hình ảnh</label><br />
        <input type="file" name="image" class="form-control"><br />
        <label class="form-label">Mô tả</label><br />
        <textarea name="description" class="form-control" rows="5" required></textarea><br />
        <button type="submit" class="btn btn-primary mt-3">Thêm sản phẩm</button>
    </div>
</form>