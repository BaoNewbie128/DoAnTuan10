<?php
    session_start();
    include __DIR__ . "/../config/db.php";
    if(!isset($_SESSION["user_id"])){
        header("Location: ../login.php");
        exit;
    }
    $user_id = $_SESSION["user_id"];
     $sql = "SELECT id FROM cart WHERE user_id = $user_id";
          $result=$conn->query($sql);
   if($result->num_rows === 0){
        $cart_id = 0; 
        $result2 = null; 
        
    } else {
         $cart_id = $result->fetch_assoc()["id"];
         $sql2 = "SELECT ci.product_id,ci.quantity,p.brand,p.model,p.image,p.price
                  FROM cart_items ci 
                  JOIN products p ON ci.product_id = p.id 
                  WHERE ci.cart_id = $cart_id";
         $result2 = $conn->query($sql2); // Biến $result2 đã được định nghĩa ở đây
    }

    if(isset($_GET['action']) && isset($_GET['id']) && $cart_id > 0){
        $action = $_GET['action'];
        $product_id = (int)$_GET['id'];
        
        if($action == 'delete'){
            $sql_quantity = "SELECT quantity FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
            $result_quantity = $conn->query($sql_quantity);
            if($result_quantity->num_rows >0){
                $current_quantity = $result_quantity->fetch_assoc()["quantity"];
                if($current_quantity >1){
                    $sql_update_quantity = "UPDATE cart_items 
                                        SET quantity = quantity - 1 
                                        WHERE cart_id = $cart_id AND product_id = $product_id";
                    if($conn->query($sql_update_quantity)===true){
                        $_SESSION['message'] = "Đã loại 1 món ra khỏi giỏ hàng";
                    }else{
                        $_SESSION['message'] = "Lỗi khi cập nhật số lượng: " . $conn->error;
                    }
                }else if($current_quantity ==1){
                        $sql_delete = "DELETE FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
             if($conn->query($sql_delete)===true){
                $_SESSION['message'] = "Xóa sản phẩm khỏi giỏ hàng thành công.";
            }else{
                $_SESSION['message'] = "Lỗi khi xóa sản phẩm: " . $conn->error;
            }
            
                }
            }     
        } else{
            $_SESSION['message'] = "Sản phẩm không tồn tại.";
        }
        header("Location: cart_item.php");
            exit;
    } 
?>
<!DOCTYPE html>
<html>

<head>
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h2 class="mb-4">Giỏ hàng của bạn</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Ảnh</th>
                    <th>Tên Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
        $total = 0;
        while ($row = $result2->fetch_assoc()):
            $subtotal = $row["price"] * $row["quantity"];
            $total += $subtotal;
        ?>
                <tr>
                    <td width="120"><img src="../<?= $row['image'] ?>" width="100"></td>
                    <td><?= $row["brand"] . " " . $row["model"] ?></td>
                    <td><?= $row["quantity"] ?></td>
                    <td><?= number_format($row["price"]) ?>₫</td>
                    <td><?= number_format($subtotal) ?>₫</td>
                    <td>
                        <a href="cart_item.php?action=delete&id=<?= $row['product_id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Xóa sản phẩm này khỏi giỏ?');">
                            Xóa
                        </a>
                    </td>
                </tr>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Quay lại</a>
        <h3 class="text-end">Tổng cộng: <strong class="text-danger"><?= number_format($total) ?>₫</strong></h3>
        <a href="order.php" class="btn btn-secondary text-end">Đặt hàng</a>
    </div>

</body>

</html>