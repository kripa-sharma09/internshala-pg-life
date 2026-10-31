<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'PG listings';
$citySlug = trim($_GET['city'] ?? '');
$sort = $_GET['sort'] ?? 'rating_desc';
$sortOptions = [
    'rating_desc' => 'p.rating DESC',
    'rating_asc' => 'p.rating ASC',
    'rent_asc' => 'p.rent ASC',
    'rent_desc' => 'p.rent DESC',
];
$orderBy = $sortOptions[$sort] ?? $sortOptions['rating_desc'];
$sql = "SELECT p.*, c.name AS city_name, c.slug AS city_slug FROM properties p JOIN cities c ON c.id=p.city_id";
$params = [];
if ($citySlug !== '') {
    $sql .= " WHERE c.slug = ?";
    $params[] = $citySlug;
}
$sql .= " ORDER BY {$orderBy}, p.name";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$properties = $stmt->fetchAll();
$cityName = $citySlug ? ($properties[0]['city_name'] ?? ucfirst(str_replace('-', ' ', $citySlug))) : 'All cities';
$interestedIds = [];
if (is_logged_in()) {
    $s = $pdo->prepare("SELECT property_id FROM interested_properties WHERE user_id=?");
    $s->execute([$_SESSION['user_id']]);
    $interestedIds = array_map('intval', array_column($s->fetchAll(), 'property_id'));
}
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner listings-banner"><div class="container"><p class="eyebrow">FIND YOUR SPACE</p><h1>PGs in <?= e($cityName) ?></h1><p class="text-secondary mb-0"><?= count($properties) ?> place(s) to explore</p></div></section>
<section class="container py-4">
  <form id="sort-form" class="filter-bar mb-4" method="get">
    <?php if ($citySlug): ?><input type="hidden" name="city" value="<?= e($citySlug) ?>"><?php endif; ?>
    <img src="img/filter.png"
     alt=""
     class="filter-icon"
     aria-hidden="true">

<label for="sort" class="fw-semibold">Sort by</label>
    <select id="sort" name="sort" class="form-select">
      <option value="rating_desc" <?= $sort==='rating_desc'?'selected':'' ?>>Highest rating</option>
      <option value="rating_asc" <?= $sort==='rating_asc'?'selected':'' ?>>Lowest rating</option>
      <option value="rent_asc" <?= $sort==='rent_asc'?'selected':'' ?>>Rent: low to high</option>
      <option value="rent_desc" <?= $sort==='rent_desc'?'selected':'' ?>>Rent: high to low</option>
    </select>
   <?php if (str_ends_with($sort, '_asc')): ?>
    <img src="img/asc.png"
         alt="Ascending order"
         class="sort-direction-icon">
<?php elseif (str_ends_with($sort, '_desc')): ?>
    <img src="img/desc.png"
         alt="Descending order"
         class="sort-direction-icon">
<?php endif; ?>
    
    <a href="property_list.php" class="btn btn-outline-secondary">All cities</a>
  </form>
  <?php if (!$properties): ?>
    <div class="empty-state"><h2 class="h4">No PGs found</h2><p>Try another city or view all available properties.</p><a href="property_list.php" class="btn btn-primary">View all PGs</a></div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($properties as $property): ?>
        <?php $isInterested = in_array((int)$property['id'], $interestedIds, true); ?>
        <?php require __DIR__ . '/includes/property_card.php'; ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
