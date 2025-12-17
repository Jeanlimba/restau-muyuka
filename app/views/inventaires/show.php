<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Détails de l'Inventaire du <?= htmlspecialchars(date('d/m/Y', strtotime($inventaire['date_inventaire']))) ?>
                </h2>
                <div class="text-muted mt-1">
                    Statut: <span class="badge bg-success-lt">Terminé</span>
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/inventaires" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à l'historique
                </a>
                <a href="/inventaires/export/excel/<?= $inventaire['id'] ?>" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Exporter
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <?php if (!empty($inventaire['conclusion'])): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Conclusion générale de l'inventaire</h5>
                <p class="text-muted"><?= nl2br(htmlspecialchars($inventaire['conclusion'])) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
             <div class="card-header">
                <h3 class="card-title">Résumé des Écarts</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th class="text-center">Stock Théorique</th>
                            <th class="text-center">Stock Physique</th>
                            <th class="text-center">Écart</th>
                            <th>Justification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventaire['lignes'] as $ligne): ?>
                        <tr>
                            <td><?= htmlspecialchars($ligne['article_nom']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($ligne['stock_theorique']) ?></td>
                            <td class="text-center fw-bold"><?= htmlspecialchars($ligne['stock_physique']) ?></td>
                            <td class="text-center">
                                <?php 
                                    $ecart = $ligne['ecart'];
                                    $ecart_class = 'text-success';
                                    if ($ecart < 0) $ecart_class = 'text-danger';
                                    if ($ecart == 0) $ecart_class = 'text-muted';
                                ?>
                                <span class="fw-bold <?= $ecart_class ?>">
                                    <?= $ecart > 0 ? '+' : '' ?><?= htmlspecialchars($ecart) ?>
                                </span>
                            </td>
                            <td class="text-muted small">
                                <?= htmlspecialchars($ligne['justification'] ?? 'N/A') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
