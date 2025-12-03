<?php
    session_start();
    include __DIR__ . "/../config/db.php";
    if(!isset($_SESSION["user_id"])){
        header("Location: ../login.php");
        exit;
    }
    $user_id = $_SESSION["user_id"];
     $sql = "SELECT id,total,status,created_at FROM orders WHERE user_id = $user_id ORDER BY id DESC";
    $orders=$conn->query($sql);
    $orderlist = [];
    if($orders && $orders->num_rows >0){
        while($order = $orders->fetch_assoc()){
            $order_item_id = $order['id'];
            $sql2 = "SELECT oi.product_id,oi.quantity,p.brand,p.model,p.image,p.price
                     FROM order_items oi 
                     JOIN products p ON oi.product_id = p.id 
                     WHERE oi.order_id = $order_item_id";
            $item2 = $conn->query($sql2);
            $orderlist[] = ['order' => $order, 'items' => $item2];
        }
    }
    if(isset($_GET['action'])  && isset($_GET['id'])){
        $action = $_GET['action'];
        $order_id = intval($_GET['id']);
        $isItemReturn_query = $conn->query("SELECT product_id,quantity FROM order_items WHERE order_id = $order_id");
        if($isItemReturn_query->num_rows > 0){
            while($item = $isItemReturn_query->fetch_assoc()){
                $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $conn->query("UPDATE products SET stock = stock + $quantity WHERE id = $product_id");
            }  
        }
        if($action == 'delete_all'){
            $sql_delete = "DELETE FROM order_items WHERE order_id = $order_id";
            $sql_delete_order = "DELETE FROM orders WHERE id = $order_id";
            if($conn->query($sql_delete)===true && $conn->query($sql_delete_order)===true){
                $_SESSION['message'] = "Hủy đơn hàng thành công.";
            }else{
                $_SESSION['message'] = "Lỗi khi hủy đơn hàng" . $conn->error;
            }
        }
     header("Location: order_items.php");
    exit; 
    } 
?>
<!DOCTYPE html>
<html>

<head>
    <title>Đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <a href="dashboard.php" class="btn btn-secondary">Quay lại</a>
        <br />
        <br />
        <h2 class="mb-4">Đơn hàng của bạn</h2>
        <?php if(empty($orderlist)):?>
        <tr>
            <td colspan="5" class="text-center text-muted">Không có sản phẩm nào</td>
        </tr>
        <?php endif; ?>
        <?php foreach($orderlist as $orderData):?>
        <?php
                $order = $orderData['order'];
                $items = $orderData['items'];
                $total = 0;
                $statusTrans = [
                    'pending' => 'Chưa xử lý',
                    'paid' => 'Đã thanh toán',
                    'shipping' => 'Đang giao hàng',
                    'completed' => 'Hoàn thành',
                    'canceled' => 'Đã hủy'
                ];
        ?>
        <h4>Đơn hàng #<?= $order['id'] ?> - Ngày đặt hàng: <?= $order['created_at'] ?> - Trạng thái:
            <?= $statusTrans[$order['status']] ?? 'Không xác định' ?></h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Ảnh</th>
                    <th>Tên Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php if($items->num_rows >0):?>
                <?php while($row = $items->fetch_assoc()):
                $subtotal = $row["price"] * $row["quantity"];
                $total += $subtotal;
                ?>
                <tr>
                    <td width="120"><img src="../images/<?= $row['image'] ?>" width="100"></td>
                    <td><?= $row["brand"] . " " . $row["model"] ?></td>
                    <td><?= $row["quantity"] ?></td>
                    <td><?= number_format($row["price"]) ?>₫</td>
                    <td><?= number_format($subtotal) ?>₫</td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Không có sản phẩm nào</td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="5">
                        <h3 class="text-end">Tổng cộng: <strong
                                class="text-danger"><?= number_format($total) ?>₫</strong></h3>
                    </td>
                </tr>
            </tbody>
        </table>
        <a href="order_items.php?action=delete_all&id=<?= $order['id'] ?>" class="btn btn-danger btn-sm"
            onclick="return confirm('Bạn muốn hủy đơn hàng này?');">
            Hủy đơn đặt hàng
        </a>
        <?php endforeach; ?>
        <br />
        <br />

        <a href="dashboard.php" class="btn btn-secondary">Quay lại</a>
    </div>
    <br />

</body>

</html>