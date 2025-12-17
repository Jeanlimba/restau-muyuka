<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Détails de la Vente
                </h2>
                <div class="text-muted mt-1">Vente N°: <?= htmlspecialchars($vente['numero_vente']) ?></div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/ventes" class="btn btn-outline-secondary">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Informations Générales</h3>
            </div>
            <div class="card-body">
                <p><strong>Date:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($vente['created_at']))) ?></p>
                <p><strong>Table:</strong> <?= htmlspecialchars($vente['table_nom']) ?> (Zone: <?= htmlspecialchars($vente['table_zone']) ?>)</p>
                <p><strong>Statut:</strong> <?= ucfirst($vente['statut']) ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Articles Vendus</h3>
            </div>
            <div class="card-body p-0">
                <?php if (isset($vente['commandes']) && !empty($vente['commandes'])): ?>
                    <?php foreach ($vente['commandes'] as $index => $commande): ?>
                        <div class="card-header pt-3 pb-2">
                            <h4 class="card-title mb-0">Commande #<?= $index + 1 ?> 
                                <span class="text-muted small">(<?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?>)</span>
                            </h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter table-sm">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($commande['lignes'] as $ligne): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($ligne['article_nom']) ?></td>
                                        <td><?= htmlspecialchars($ligne['quantite']) ?></td>
                                        <td><?= htmlspecialchars(number_format($ligne['prix_unitaire_ht'], 0, ',', ' ')) ?> Fc</td>
                                        <td><?= htmlspecialchars(number_format($ligne['prix_unitaire_ht'] * $ligne['quantite'], 0, ',', ' ')) ?> Fc</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted p-4">
                        <p>Aucun article vendu dans cette vente.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <h3 class="mb-0">Total TTC: <?= htmlspecialchars(number_format($vente['total'], 0, ',', ' ')) ?> Fc</h3>
                </div>
            </div>
        </div>
    </div>
</div>
