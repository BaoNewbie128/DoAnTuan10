<?php include __DIR__ . "/../includes/auth_check.php"; 
    require_once __DIR__ . "/../config/db.php";
    $products =[];
    $error_message ="";
    $unique_brands = [];
    function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . '₫';
}
function truncate_description($text, $limit = 100) {
    if (strlen($text) > $limit) {
        $text = substr($text, 0, $limit);
        $text = substr($text, 0, strrpos($text, ' '));
        return $text . '...';
    }
    return $text;
}
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $filter_brand = isset($_GET['brand']) ? $conn->real_escape_string($_GET['brand']) : '';
    $where_clauses = [];
    if (!empty($search_query)) {
        $where_clauses[] = "(brand LIKE '%$search_query%' OR model LIKE '%$search_query%')";
    }
    if (!empty($filter_brand) && $filter_brand !== 'all') {
        $where_clauses[] = "brand = '$filter_brand'";
    }

    $where_sql = count($where_clauses) > 0 ? ' WHERE ' . implode(' AND ', $where_clauses) : '';
    
    $sql = "SELECT id, brand, model, scale, price, stock, image, description FROM products " . $where_sql . "ORDER BY id DESC";
$result = $conn->query($sql);
if ($result === FALSE) {
    $error_message = '<div class="alert alert-danger text-center">Lỗi truy vấn: ' . $conn->error . '</div>';
} else {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
            if (!in_array($row['brand'], $unique_brands)) {
                $unique_brands[] = $row['brand'];
            }
        }
    }
    $result->free();
}
$conn->close();
?>

<h2>Chào <?php echo $_SESSION["username"]; ?></h2>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDM Model Shop - Sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/../assets/css/style.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">JDM World</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn btn-secondary btn-sm text-white" href="#"
                            id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hãng xe:
                            <?= !empty($filter_brand) && $filter_brand !== 'all' ? htmlspecialchars($filter_brand) : 'Tất cả' ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item"
                                    href="?search=<?= htmlspecialchars($search_query) ?>&brand=all">Tất cả</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php foreach ($unique_brands as $brand): ?>
                            <li><a class="dropdown-item"
                                    href="?search=<?= htmlspecialchars($search_query) ?>&brand=<?= urlencode($brand) ?>"><?= htmlspecialchars($brand) ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>

                <form class="d-flex me-3" method="GET">
                    <input type="hidden" name="brand" value="<?= htmlspecialchars($filter_brand) ?>">
                    <input class="form-control me-2" type="search" placeholder="Tìm tên xe/hãng" aria-label="Search"
                        name="search" value="<?= htmlspecialchars($search_query) ?>">
                    <button class="btn btn-outline-light" type="submit">Tìm</button>
                </form>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart_item.php">Giỏ hàng</a></li>
                    <li class="nav-item"><a class="nav-link" href="/logout.php">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2 class="mb-4 fw-bold text-center">Các mô hình xe JDM</h2>
        <?= $error_message ?>
        <div class="row" id="productList">
            <?php
                if (!empty($products)):
            foreach ($products as $p):
                
                $price = format_currency($p['price']);
                $image_path = htmlspecialchars($p['image']); 
                $full_name = htmlspecialchars($p['brand']) . ' ' . htmlspecialchars($p['model']);
                $short_description = truncate_description($p['description'], 100);
                $modal_id = 'modal-' . $p['id'];
            ?>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="/../<?= $image_path ?>?>" class="card-img-top" alt="<?= $full_name ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $full_name ?></h5>
                        <p class="card-text text-muted">Tỉ lệ: <?= htmlspecialchars($p['scale']) ?></p>
                        <p class="card-text text-muted"> <?php 
                        if($p['stock'] > 0){
                             echo "Số lượng:". htmlspecialchars($p['stock']) ;
                        } else {
                            echo "Hết hàng";}
                           ?></p>
                        <p class="fw-bold text-danger"><?= $price ?></p>
                        <p class="card-text small mb-2">
                            <strong>Chi tiết về xe:</strong> <?= $short_description ?>

                            <?php if (strlen($p['description']) > 100): ?>
                            <a href="#" class="text-primary text-decoration-none fw-bold" data-bs-toggle="modal"
                                data-bs-target="#<?= $modal_id ?>">
                                Xem thêm
                            </a>
                            <?php endif; ?>
                        </p>
                        <a href="cart_add.php?product_id=<?= $p['id'] ?>" class="btn btn-primary w-100">Thêm vào giỏ
                            hàng</a>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="<?= $modal_id ?>Label"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="<?= $modal_id ?>Label">Chi tiết về xe: <?= $full_name ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?= nl2br(htmlspecialchars($p['description'])) ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; 
            else:?>
            <?php endif;?>
        </div>
    </div>
</body>

</html>