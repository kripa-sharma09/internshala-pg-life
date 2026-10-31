<?php
require_once __DIR__ . '/includes/config.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT p.*, c.name AS city_name, c.slug AS city_slug FROM properties p JOIN cities c ON c.id=p.city_id WHERE p.id=?");
$stmt->execute([$id ?: 0]);
$property = $stmt->fetch();
if (!$property) {
    http_response_code(404);
    $pageTitle = 'Property not found';
    require __DIR__ . '/includes/header.php';
    echo '<section class="container py-5"><h1>Property not found</h1><p>This listing may have been removed.</p><a href="property_list.php">Browse properties</a></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$pageTitle = $property['name'];
$amenityStmt = $pdo->prepare("SELECT a.name, a.icon_path FROM amenities a JOIN property_amenities pa ON pa.amenity_id=a.id WHERE pa.property_id=? ORDER BY a.name");
$amenityStmt->execute([$id]);
$amenities = $amenityStmt->fetchAll();
$interestedStmt = $pdo->prepare("SELECT COUNT(*) FROM interested_properties WHERE property_id=?");
$interestedStmt->execute([$id]);
$interestedCount = (int)$interestedStmt->fetchColumn();
$isInterested = false;
if (is_logged_in()) {
    $s = $pdo->prepare("SELECT 1 FROM interested_properties WHERE user_id=? AND property_id=?");
    $s->execute([$_SESSION['user_id'], $id]);
    $isInterested = (bool)$s->fetchColumn();
}
require __DIR__ . '/includes/header.php';
?>
<div class="container py-4">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Home</a></li><li class="breadcrumb-item"><a href="property_list.php?city=<?= e($property['city_slug'] ?? '') ?>">PG listings</a></li><li class="breadcrumb-item active" aria-current="page"><?= e($property['name']) ?></li></ol></nav>
  <div class="row g-4 align-items-start">
    <div class="col-lg-7">
      <img class="detail-image" src="<?= e($property['image_path'] ?: 'img/properties/placeholder.svg') ?>" alt="<?= e($property['name']) ?>">
      <div class="mt-4"><h2 class="h4">About this PG</h2><p><?= nl2br(e($property['description'])) ?></p></div>
      <h2 class="h4 mt-4">Amenities</h2>
      <div class="amenity-grid">
        <?php foreach ($amenities as $amenity): ?>
          <div class="amenity-item">
            <?php if ($amenity['icon_path']): ?><img src="<?= e($amenity['icon_path']) ?>" alt=""><?php endif; ?>
            <span><?= e($amenity['name']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="detail-panel">
        <div class="d-flex justify-content-between gap-3"><h1 class="h3"><?= e($property['name']) ?></h1><span class="rating-badge"><?= number_format((float)$property['rating'], 1) ?> ★</span></div>
        <p class="text-secondary"><?= e($property['address']) ?>, <?= e($property['city_name']) ?></p>
        <div class="rent display-6">₹<?= number_format((float)$property['rent']) ?><span class="fs-6 text-secondary"> / month</span></div>
        <?php
$gender = $property['gender_preference'];

$genderIcon = [
    'Male' => 'img/male.png',
    'Female' => 'img/female.png',
    'Unisex' => 'img/unisex.png'
];

$icon = $genderIcon[$gender] ?? 'img/unisex.png';
?>

<hr>
<p>
    <strong>For:</strong>
    <img src="<?= e($icon) ?>"
         alt="<?= e($gender) ?>"
         style="width: 28px; height: 28px; object-fit: contain; vertical-align: middle;">
    <?= e($gender) ?>
</p>
        <p><strong>Bedrooms:</strong> <?= (int)$property['bedrooms'] ?></p>
        <p><strong>Interested users:</strong> <?= $interestedCount ?></p>
        <?php if (is_logged_in()): ?>
          <form action="api/toggle_favourite.php" method="post">
            <input type="hidden" name="property_id" value="<?= (int)$property['id'] ?>">
            <input type="hidden" name="return_to" value="<?= e($_SERVER['REQUEST_URI'] ?? 'property_detail.php?id='.$id) ?>">
            <button class="btn <?= $isInterested?'btn-danger':'btn-outline-danger' ?> w-100" type="submit"><?= $isInterested?'♥ Remove from interested':'♡ Mark as interested' ?></button>
          </form>
        <?php else: ?>
          <a class="btn btn-primary w-100" href="login.php">Log in to mark interested</a>
        <?php endif; ?>
        <p class="small text-secondary mt-3 mb-0">Demo listing. Confirm rent, availability, and facilities directly with the property owner.</p>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
