<?php

include __DIR__ ."/../includes/auth_check.php";
require_once __DIR__ . "/../config/db.php";
   if($_SESSION["role"] !== 'admin'){
       die("<div class='alert alert-danger text-center'>Bạn không có quyền truy cập trang này!</div>");
   }
    $message = "";
    // read
    $sql = "SELECT r.id AS review_id,r.rating, r.comment, r.created_at, u.username,p.brand,p.model,p.image 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.id 
                    JOIN products p ON r.product_id = p.id
                    ORDER BY r.created_at DESC";
    $reviews = $conn->query($sql);

      // edit
    if(isset($_POST['edit_review_id'])){
        $review_id = intval($_POST['edit_review_id']);
        $comment = $conn->real_escape_string($_POST['edit_comment']);
        $rating = intval($_POST['edit_rating'] ?? 10);
        if($rating < 1 || $rating > 10){
          $rating = 10;
        }
        $sql_edit = "UPDATE reviews SET comment='$comment', rating=$rating WHERE id = $review_id";
        if($conn->query($sql_edit) === TRUE){
            $message = "<div class='alert alert-success text-center'>Đánh giá đã được cập nhật !</div>";
        }else{
            $message = "<div class='alert alert-danger text-center'> Lỗi cập nhật ! </div> ";
        }
    }
      // delete
    if(isset($_GET['delete_review'])){
        $review_id = intval($_GET['delete_review']);
           if( $conn->query("DELETE FROM reviews WHERE id = $review_id")){
            $message = "<div class='alert alert-success text-center'>Đánh giá đã được xóa !</div>";
        }else{
            $message = "<div class='alert alert-danger text-center'>Không thể xóa đánh giá này !</div>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đánh giá sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
    <div class="app-container">
        <h2 class="page-title text-primary">Quản lý đánh giá</h2>
        <?= $message ?>

        <?php if ($reviews->num_rows > 0): ?>
        <?php while ($r = $reviews->fetch_assoc()): ?>
        <div class="card p-3 mb-3 shadow-sm">
            <div class="row g-3 align-items-start">
                <div class="col-12 col-md-3">
                    <img src="../images/<?= $r['image'] ?>" class="img-fluid rounded w-100 mb-2 mb-md-0"
                        style="height:160px; object-fit:cover;"
                        alt="<?= htmlspecialchars($r['brand'].' '.$r['model']) ?>">
                </div>
                <div class="col-12 col-md-9">
                    <strong
                        class="text-primary fs-5 d-block mb-2"><?= htmlspecialchars($r['brand'] . ' ' . $r['model']) ?></strong>
                    <p class="mb-1"><strong>Người đánh giá:</strong> <?= htmlspecialchars($r['username']) ?></p>
                    <p class="mb-1"><strong>Điểm:</strong> <?= $r['rating'] ?>/10</p>
                    <p class="mb-1"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                    <small class="text-muted d-block mb-2">Ngày: <?= $r['created_at'] ?></small>
                    <div class="mt-2 d-flex gap-2">
                        <button class="btn btn-warning btn-sm"
                            onclick="openEditModal(<?= $r['review_id'] ?>, <?= $r['rating'] ?>, `<?= htmlspecialchars($r['comment'], ENT_QUOTES) ?>`)">✏
                            Sửa</button>
                        <a href="?delete_review=<?= $r['review_id'] ?>" onclick="return confirm('Xóa đánh giá này?')"
                            class="btn btn-danger btn-sm">🗑 Xóa</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="alert alert-info text-center">Chưa có đánh giá nào.</div>
        <?php endif; ?>
    </div>


    <!-- EDIT MODAL -->
    <div class="modal fade" id="editReviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Sửa đánh giá</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="edit_review_id" id="edit_review_id">

                        <label class="form-label fw-bold">Điểm (1–10)</label>
                        <select name="edit_rating" id="edit_rating" class="form-select mb-3">
                            <?php for ($i = 10; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>

                        <label class="form-label fw-bold">Nội dung đánh giá</label>
                        <textarea name="edit_comment" id="edit_comment" class="form-control" rows="4"
                            required></textarea>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Lưu</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>

                </form>
            </div>


            <script>
            function openEditModal(id, rating, comment) {
                document.getElementById('edit_review_id').value = id;
                document.getElementById('edit_rating').value = rating;
                document.getElementById('edit_comment').value = comment;

                var modal = new bootstrap.Modal(document.getElementById('editReviewModal'));
                modal.show();
            }
            </script>

</body>

</html>