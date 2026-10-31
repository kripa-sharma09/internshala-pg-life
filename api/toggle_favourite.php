<?php
require_once __DIR__ . '/../includes/config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}
$propertyId = filter_input(INPUT_POST, 'property_id', FILTER_VALIDATE_INT);
$returnTo = $_POST['return_to'] ?? '../index.php';
// Only allow local relative URLs, preventing open redirects.
if (!$returnTo || preg_match('#^(?:https?:)?//#i', $returnTo) || str_contains($returnTo, "\r") || str_contains($returnTo, "\n")) {
    $returnTo = '../index.php';
}
if (!$propertyId) {
    set_flash('Invalid property.');
    header('Location: ../index.php'); exit;
}
$check = $pdo->prepare("SELECT id FROM properties WHERE id=?");
$check->execute([$propertyId]);
if (!$check->fetch()) {
    set_flash('Property not found.');
    header('Location: ../index.php'); exit;
}
$exists = $pdo->prepare("SELECT 1 FROM interested_properties WHERE user_id=? AND property_id=?");
$exists->execute([$_SESSION['user_id'], $propertyId]);
if ($exists->fetch()) {
    $delete = $pdo->prepare("DELETE FROM interested_properties WHERE user_id=? AND property_id=?");
    $delete->execute([$_SESSION['user_id'], $propertyId]);
    set_flash('Removed from interested properties.');
} else {
    $insert = $pdo->prepare("INSERT IGNORE INTO interested_properties (user_id, property_id) VALUES (?,?)");
    $insert->execute([$_SESSION['user_id'], $propertyId]);
    set_flash('Added to interested properties.');
}
if (str_starts_with($returnTo, '/')) {
    header('Location: '.$returnTo);
} else {
    header('Location: ../'.ltrim($returnTo, './'));
}
exit;
