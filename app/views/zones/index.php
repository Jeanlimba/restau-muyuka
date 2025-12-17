<?php
$titre = "Sélection de la Zone";
ob_start();
?>

<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Choisir une zone de vente
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <?php if (empty($zones)): ?>
                <div class="col-12">
                    <div class="alert alert-warning">
                        Aucune zone n'a été configurée.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($zones as $zone): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="card-title mb-3"><?= htmlspecialchars($zone['nom']) ?></h3>
                                <p class="text-muted"><?= htmlspecialchars($zone['description']) ?></p>
                                <a href="/zones/<?= $zone['id'] ?>/tables" class="btn btn-primary">
                                    Voir les tables
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$contenu = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>
