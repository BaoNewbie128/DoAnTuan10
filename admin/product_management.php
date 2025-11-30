<?php
    require __DIR__ . "/../config/db.php";
    $products = [];
    $unique_brands = [];
    $error_message ="";

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
<h2 style="color: blue">Quản lý sản phẩm</h2>
<form method="GET" class="d-flex gap-2 mb-4">
    <input type="hidden" name="view" value="products">

    <select name="brand" class="form-select" style="max-width:200px">
        <option value="all">Tất cả hãng</option>
        <?php foreach ($unique_brands as $b): ?>
        <option value="<?= $b ?>" <?= ($filter_brand==$b?"selected":"") ?>><?= $b ?></option>
        <?php endforeach; ?>
    </select>

    <input name="search" class="form-control" placeholder="Tìm tên xe/hãng"
        value="<?= htmlspecialchars($search_query) ?>" size="20">

    <button class="btn btn-primary">Lọc</button>
</form>

<div class="row">
    <?php foreach ($products as $p): ?>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
            <img src="/../<?= $p['image'] ?>" class="card-img-top">
            <div class="card-body">
                <h5><?= $p["brand"] . " " . $p["model"] ?></h5>
                <p class="text-muted">Tỉ lệ: <?= $p["scale"] ?></p>
                <p class="text-muted">Số lượng: <?= $p["stock"] ?></p>
                <p class="fw-bold text-danger"><?= format_currency($p["price"]) ?></p>
            </div>
        </div>
    </div>
    <?php endforeach;  ?>
</div>