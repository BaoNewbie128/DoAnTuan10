<?php
$path = realpath(__DIR__ . '/../LapTrinhWebThayNghia');

if (!$path) {
    die("Không tìm thấy thư mục buổi học!");
}

$dirs = array_filter(glob($path . '/*'), 'is_dir');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bài thực hành</title>
</head>

<body>
    <h2>Danh sách các buổi thực hành</h2>
    <ul>
        <?php foreach ($dirs as $dir): 
            $name = basename($dir); ?>
        <li>
            <a href="view.php?folder=<?= urlencode($name) ?>">
                <?= $name ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>