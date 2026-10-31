<?php
// Expects $property and optionally $isInterested (boolean).
$isInterested = $isInterested ?? false;
$imagePath = !empty($property['image_path']) ? $property['image_path'] : 'img/properties/placeholder.svg';
?>
<div class="col">
  <article class="card property-card h-100">
    <img src="<?= e($imagePath) ?>" class="card-img-top property-thumb" alt="<?= e($property['name']) ?>">
    <div class="card-body">
      <div class="d-flex justify-content-between gap-2 align-items-start">
        <h2 class="h5 card-title mb-1"><?= e($property['name']) ?></h2>
        <span class="rating-badge"><?= number_format((float)$property['rating'], 1) ?> ★</span>
      </div>
      <p class="text-secondary small mb-3"><?= e($property['address']) ?>, <?= e($property['city_name'] ?? '') ?></p>
      <div class="rent-line">
    <img src="img/rupee.png" alt="Rupees" class="rupee-icon">
    <span class="rent"><?= number_format((float)$property['rent']) ?></span>
    <span class="text-secondary small">/ month</span>
</div>
      <p class="small text-secondary mt-2 mb-3"><?= e($property['gender_preference']) ?> · <?= (int)$property['bedrooms'] ?> room(s)</p>
      <div class="d-flex gap-2 align-items-center">
        <a class="btn btn-primary flex-grow-1" href="property_detail.php?id=<?= (int)$property['id'] ?>">View details</a>
        <?php if (is_logged_in()): ?>
          <form action="api/toggle_favourite.php" method="post" class="m-0">
            <input type="hidden" name="property_id" value="<?= (int)$property['id'] ?>">
            <input type="hidden" name="return_to" value="<?= e($_SERVER['REQUEST_URI'] ?? 'index.php') ?>">
            <button class="btn <?= $isInterested ? 'btn-danger' : 'btn-outline-danger' ?> heart-button"
        type="submit"
        aria-label="<?= $isInterested ? 'Remove from interested' : 'Mark interested' ?>">
    <img src="img/like.png" alt="Favourite" class="like-icon">
</button>
          </form>
        <?php else: ?>
          <a class="btn btn-outline-danger heart-button"
   href="login.php"
   aria-label="Log in to mark interested">
    <img src="img/like.png" alt="Favourite" class="like-icon">
</a>
        <?php endif; ?>
      </div>
    </div>
  </article>
</div>
