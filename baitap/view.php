<?php
$folder = $_GET['folder'] ?? '';

$path = realpath(__DIR__ . '/../LapTrinhWebThayNghia/' . $folder);

if (!$path || !is_dir($path)) {
    die("Thư mục không tồn tại.");
}

$files = array_filter(scandir($path), function($file) use ($path) {
    return $file !== '.' && $file !== '..';
});
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($folder) ?></title>
</head>

<body>
    <h2>Bài thực hành <?= htmlspecialchars($folder) ?></h2>
    <a href="index.php">⬅ Quay lại danh sách buổi</a>

    <ul>
        <?php foreach ($files as $f): ?>
        <li>
            <a href="<?= "/LapTrinhWebThayNghia/$folder/$f" ?>" target="_blank">
                <?= $f ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>