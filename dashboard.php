
<?php
require_once __DIR__ . '/includes/config.php';
require_login();

// Create a security token for the photo upload form.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pageTitle = 'Dashboard';

$stmt = $pdo->prepare(
    "SELECT id, name, email, phone, gender, college, created_at, profile_image
     FROM users WHERE id = ?"
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$stmt = $pdo->prepare(
    "SELECT p.*, c.name AS city_name
     FROM interested_properties ip
     JOIN properties p ON p.id = ip.property_id
     JOIN cities c ON c.id = p.city_id
     WHERE ip.user_id = ?
     ORDER BY ip.created_at DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$properties = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <p class="eyebrow">YOUR ACCOUNT</p>
        <h1>Dashboard</h1>
        <p class="text-secondary mb-0">Manage your profile and saved PGs.</p>
    </div>
</section>

<section class="container py-4">

    <div class="profile-panel mb-5">

        <div>
            <?php if (!empty($user['profile_image'])): ?>
                <img
    src="<?= e($user['profile_image']) ?>"
    alt="Profile photo"
    class="profile-photo"
    style="width:100px; height:100px; max-width:100px; max-height:100px; object-fit:cover; border-radius:50%; display:block; flex-shrink:0;">
            <?php else: ?>
                <div class="profile-avatar">
                    <?= e(mb_strtoupper(mb_substr($user['name'] ?? 'U', 0, 1))) ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <h2 class="h4 mb-1"><?= e($user['name'] ?? '') ?></h2>

            <p class="text-secondary mb-2">
                <?= e($user['email'] ?? '') ?>
            </p>

            <div class="small mb-3">
                <?= e($user['phone'] ?? 'No phone added') ?>
                ·
                <?= e($user['college'] ?? 'No college/workplace added') ?>
            </div>
            <div class="small text-secondary mb-3 d-flex align-items-center gap-2">
            <img src="/pglife/img/calendar.png"
            alt="Calendar"
            width="40"
            height="40"
            style="display:inline-block; background:#1769aa; padding:5px; border-radius:8px; object-fit:contain;">
    <span>
        Member since <?= date('F Y', strtotime($user['created_at'])) ?>
    </span>
</div>

            <form action="api/upload_profile.php"
                  method="post"
                  enctype="multipart/form-data">

                <input type="hidden"
                       name="csrf_token"
                       value="<?= e($_SESSION['csrf_token']) ?>">

                <label for="profile_image" class="form-label">
                    Choose your profile photo
                </label>

                <input
                    type="file"
                    name="profile_image"
                    id="profile_image"
                    class="form-control mb-2"
                    accept=".jpg,.jpeg,.png,.webp"
                    required>

                <button type="submit" class="btn btn-primary">
                    Upload Photo
                </button>

                <p class="small text-secondary mt-2 mb-0">
                    JPG, PNG or WebP. Maximum size: 5 MB.
                </p>

            </form>
        </div>

    </div>

    <div class="section-heading">
        <div>
            <p class="eyebrow">YOUR SHORTLIST</p>
            <h2>Interested properties</h2>
        </div>
        <span class="text-secondary"><?= count($properties) ?> saved</span>
    </div>

    <?php if (!$properties): ?>
        <div class="empty-state">
            <h3 class="h4">No saved PGs yet</h3>
            <p>Browse listings and tap the heart to save a place.</p>
            <a class="btn btn-primary" href="property_list.php">Explore PGs</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($properties as $property):
                $isInterested = true;
                require __DIR__ . '/includes/property_card.php';
            endforeach; ?>
        </div>
    <?php endif; ?>

</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
