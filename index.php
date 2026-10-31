<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Find your PG';
$cities = $pdo->query("SELECT id, name, slug FROM cities ORDER BY name")->fetchAll();
$properties = $pdo->query("SELECT p.*, c.name AS city_name FROM properties p JOIN cities c ON c.id=p.city_id ORDER BY p.rating DESC LIMIT 6")->fetchAll();
$interestedIds = [];
if (is_logged_in()) {
    $stmt = $pdo->prepare("SELECT property_id FROM interested_properties WHERE user_id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $interestedIds = array_map('intval', array_column($stmt->fetchAll(), 'property_id'));
}
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="container hero-content">
    <p class="eyebrow">YOUR NEXT CHAPTER STARTS HERE</p>
    <h1>Find a PG that feels<br class="d-none d-md-block"> like <span>home.</span></h1>
    <p class="lead">Explore paying guest accommodation for students and professionals.</p>
    <form action="property_list.php" method="get" class="search-box">
      <label class="visually-hidden" for="city">Choose a city</label>
      <select id="city" name="city" class="form-select form-select-lg" required>
        <option value="">Select your city</option>
        <?php foreach ($cities as $city): ?>
          <option value="<?= e($city['slug']) ?>"><?= e($city['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary btn-lg" type="submit">Search PGs</button>
    </form>
  </div>
</section>
<section class="container py-5">
  <div class="section-heading"><div><p class="eyebrow">EXPLORE BY LOCATION</p><h2>Popular cities</h2></div></div>
  <div class="row g-3 city-grid">
    <?php foreach ($cities as $city): ?>
      <div class="col-6 col-md-3">
        <a class="city-tile" href="property_list.php?city=<?= e($city['slug']) ?>">
         <?php
$cityImages = [
    'bengaluru' => 'img/bangalore.png',
    'hyderabad' => 'img/hyderabad.png',
    'delhi' => 'img/delhi.png',
    'mumbai' => 'img/mumbai.png'
];

$cityKey = strtolower($city['name']);
?>

<img
    src="<?= e($cityImages[$cityKey] ?? 'img/bg.png') ?>"
    alt="<?= e($city['name']) ?>"
    class="city-image"> 
          <strong><?= e($city['name']) ?></strong><span class="small text-secondary">Explore PGs →</span>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<section class="container py-4">
  <div class="section-heading"><div><p class="eyebrow">HANDPICKED FOR YOU</p><h2>Popular PGs</h2></div><a href="property_list.php" class="text-decoration-none">View all →</a></div>
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($properties as $property): ?>
      <?php $isInterested = in_array((int)$property['id'], $interestedIds, true); ?>
      <?php require __DIR__ . '/includes/property_card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
