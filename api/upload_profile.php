
<?php
require_once __DIR__ . '/../includes/config.php';
require_login();

function goBack($status) {
    header('Location: ../dashboard.php?photo=' . urlencode($status));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    goBack('invalid_request');
}

if (
    empty($_SESSION['csrf_token']) ||
    !isset($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Invalid security token. Please refresh the dashboard and try again.');
}

if (!isset($_FILES['profile_image'])) {
    goBack('no_file');
}

$file = $_FILES['profile_image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    goBack('upload_error');
}

if ($file['size'] > 5 * 1024 * 1024) {
    goBack('too_large');
}

if (!is_uploaded_file($file['tmp_name'])) {
    goBack('invalid_file');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);

$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

if (!isset($allowedTypes[$mime]) || getimagesize($file['tmp_name']) === false) {
    goBack('invalid_type');
}

$extension = $allowedTypes[$mime];
$filename = bin2hex(random_bytes(16)) . '.' . $extension;

$uploadDir = __DIR__ . '/../uploads/profile/';

if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
    goBack('save_error');
}

$destination = $uploadDir . $filename;
$relativePath = 'uploads/profile/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    goBack('save_error');
}

try {
    $stmt = $pdo->prepare(
        "UPDATE users SET profile_image = ? WHERE id = ?"
    );
    $stmt->execute([$relativePath, $_SESSION['user_id']]);
} catch (Throwable $e) {
    @unlink($destination);
    goBack('save_error');
}

header('Location: ../dashboard.php?photo=success');
exit;
