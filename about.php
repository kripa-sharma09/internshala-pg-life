
<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'About Us';
require __DIR__ . '/includes/header.php';
?>

<section class="page-banner about-banner">
    <div class="container">
        <p class="eyebrow">GET TO KNOW US</p>
        <h1>About PG Life</h1>
        <p class="text-secondary mb-0">
            Find a place that feels like home.
        </p>
    </div>
</section>

<section class="container py-5">
    <div class="row align-items-center g-4">
        <div class="col-md-6">
            <img src="img/about.jpg"
                 alt="About PG Life"
                 class="img-fluid rounded-4">
        </div>

        <div class="col-md-6">
            <p class="eyebrow">OUR PURPOSE</p>
            <h2 class="fw-bold">Making PG discovery easier</h2>

            <p class="text-secondary">
                PG Life is a learning project designed to help
                students and working professionals explore
                paying guest accommodation.
            </p>

            <p class="text-secondary">
                Browse properties, compare rents, explore
                amenities and save your favourite places.
            </p>

            <a href="property_list.php" class="btn btn-primary">
                Explore PGs
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
